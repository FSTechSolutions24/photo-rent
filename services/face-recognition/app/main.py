import hmac
import os
import threading
from pathlib import Path
from typing import List, Optional

import cv2
import numpy as np
from fastapi import Depends, FastAPI, File, Header, HTTPException, UploadFile
from pydantic import BaseModel, Field
from sklearn.cluster import DBSCAN


MODEL_DIR = Path(os.getenv("FACE_MODEL_DIR", "/models"))
YUNET_PATH = MODEL_DIR / os.getenv("FACE_YUNET_MODEL", "face_detection_yunet_2023mar.onnx")
SFACE_PATH = MODEL_DIR / os.getenv("FACE_SFACE_MODEL", "face_recognition_sface_2021dec.onnx")
MODEL_VERSION = os.getenv("FACE_MODEL_VERSION", "opencv-yunet-2023mar+sface-2021dec")
API_KEY = os.getenv("FACE_API_KEY", "")
MAX_UPLOAD_BYTES = int(os.getenv("FACE_MAX_UPLOAD_BYTES", str(12 * 1024 * 1024)))
MIN_FACE_PIXELS = int(os.getenv("FACE_MIN_FACE_PIXELS", "48"))
MIN_QUALITY = float(os.getenv("FACE_MIN_QUALITY", "0.12"))
BLUR_REFERENCE = float(os.getenv("FACE_BLUR_REFERENCE", "180"))
DETECTION_THRESHOLD = float(os.getenv("FACE_DETECTION_THRESHOLD", "0.88"))
CLUSTER_EPS = float(os.getenv("FACE_CLUSTER_EPS", "0.32"))


class ClusterFace(BaseModel):
    id: str
    embedding: List[float] = Field(min_length=1)
    quality: Optional[float] = None


class ClusterRequest(BaseModel):
    faces: List[ClusterFace]
    eps: Optional[float] = Field(default=None, gt=0, lt=2)


class OpenCvEngine:
    def __init__(self) -> None:
        if not YUNET_PATH.is_file() or not SFACE_PATH.is_file():
            raise FileNotFoundError(
                f"Required models are missing: {YUNET_PATH} and/or {SFACE_PATH}"
            )
        self.detector = cv2.FaceDetectorYN.create(
            str(YUNET_PATH), "", (320, 320), DETECTION_THRESHOLD, 0.3, 5000
        )
        self.recognizer = cv2.FaceRecognizerSF.create(str(SFACE_PATH), "")
        self.lock = threading.Lock()

    def analyze(self, image: np.ndarray) -> list:
        height, width = image.shape[:2]
        with self.lock:
            self.detector.setInputSize((width, height))
            _, detections = self.detector.detect(image)

            results = []
            for detection in detections if detections is not None else []:
                x, y, box_width, box_height = detection[:4]
                confidence = float(detection[-1])
                if min(box_width, box_height) < MIN_FACE_PIXELS:
                    continue

                aligned = self.recognizer.alignCrop(image, detection)
                if aligned is None or aligned.size == 0:
                    continue

                gray = cv2.cvtColor(aligned, cv2.COLOR_BGR2GRAY)
                sharpness = float(cv2.Laplacian(gray, cv2.CV_64F).var())
                quality = max(0.0, min(1.0, sharpness / BLUR_REFERENCE))
                if quality < MIN_QUALITY:
                    continue

                embedding = self.recognizer.feature(aligned).flatten().astype(float)
                norm = float(np.linalg.norm(embedding))
                if not np.isfinite(norm) or norm == 0:
                    continue
                embedding /= norm

                results.append({
                    "bbox": [
                        max(0.0, float(x) / width),
                        max(0.0, float(y) / height),
                        min(1.0, float(box_width) / width),
                        min(1.0, float(box_height) / height),
                    ],
                    "confidence": confidence,
                    "quality": quality,
                    "embedding": embedding.tolist(),
                })

        return results


app = FastAPI(title="Private OpenCV Face Service", version="0.1.0")
_engine = None
_engine_error = None
_engine_init_lock = threading.Lock()


def authorize(authorization: Optional[str] = Header(default=None)) -> None:
    if not API_KEY:
        return
    expected = f"Bearer {API_KEY}"
    if authorization is None or not hmac.compare_digest(authorization, expected):
        raise HTTPException(status_code=401, detail="Invalid service credential")


def engine() -> OpenCvEngine:
    global _engine, _engine_error
    if _engine is None:
        with _engine_init_lock:
            if _engine is None:
                try:
                    _engine = OpenCvEngine()
                    _engine_error = None
                except Exception as exc:
                    _engine_error = str(exc)
                    raise HTTPException(status_code=503, detail=_engine_error) from exc
    return _engine


@app.get("/health")
def health(_: None = Depends(authorize)) -> dict:
    try:
        engine()
    except HTTPException:
        return {"status": "unavailable", "model_version": MODEL_VERSION, "error": _engine_error}
    return {"status": "ok", "model_version": MODEL_VERSION}


@app.post("/analyze")
async def analyze(image: UploadFile = File(...), _: None = Depends(authorize)) -> dict:
    payload = await image.read(MAX_UPLOAD_BYTES + 1)
    if len(payload) > MAX_UPLOAD_BYTES:
        raise HTTPException(status_code=413, detail="Image is too large")

    decoded = cv2.imdecode(np.frombuffer(payload, dtype=np.uint8), cv2.IMREAD_COLOR)
    if decoded is None:
        raise HTTPException(status_code=422, detail="The upload is not a readable image")

    height, width = decoded.shape[:2]
    faces = engine().analyze(decoded)
    return {"model_version": MODEL_VERSION, "width": width, "height": height, "faces": faces}


@app.post("/cluster")
def cluster(request: ClusterRequest, _: None = Depends(authorize)) -> dict:
    if not request.faces:
        return {"model_version": MODEL_VERSION, "clusters": []}

    dimensions = {len(face.embedding) for face in request.faces}
    if len(dimensions) != 1:
        raise HTTPException(status_code=422, detail="All embeddings must have equal dimensions")

    embeddings = np.asarray([face.embedding for face in request.faces], dtype=np.float32)
    norms = np.linalg.norm(embeddings, axis=1, keepdims=True)
    if np.any(norms == 0) or not np.all(np.isfinite(embeddings)):
        raise HTTPException(status_code=422, detail="Embeddings must be finite and non-zero")
    embeddings = embeddings / norms

    labels = DBSCAN(eps=request.eps or CLUSTER_EPS, min_samples=2, metric="cosine").fit_predict(embeddings)
    next_label = int(labels.max()) + 1 if labels.size and labels.max() >= 0 else 0
    for index, label in enumerate(labels):
        if label == -1:
            labels[index] = next_label
            next_label += 1

    clusters = []
    for label in sorted(set(labels.tolist())):
        indices = np.flatnonzero(labels == label)
        vectors = embeddings[indices]
        centroid = vectors.mean(axis=0)
        centroid /= np.linalg.norm(centroid)
        representative_index = indices[int(np.argmax(vectors @ centroid))]
        clusters.append({
            "face_ids": [request.faces[index].id for index in indices],
            "representative_face_id": request.faces[int(representative_index)].id,
        })

    return {"model_version": MODEL_VERSION, "clusters": clusters}
