# Private OpenCV face service

This service performs one request per gallery image, returning every usable face
and its normalized SFace embedding. It never stores uploads.

## Model files required (not downloaded by this repository)

Place these OpenCV Zoo ONNX files in `models/`:

- `face_detection_yunet_2023mar.onnx` (YuNet face detector)
- `face_recognition_sface_2021dec.onnx` (SFace face recognizer)

Both models are distributed from the OpenCV Zoo project under Apache 2.0. Review
the upstream license and model cards before production use. Model files are
intentionally ignored by Git and must be downloaded only after approval.

## Run after adding models

```sh
docker compose up --build
```

The port is bound to localhost. Set the same non-empty `FACE_API_KEY` here and
`FACE_RECOGNITION_API_KEY` in Laravel when the service is reachable beyond a
single trusted host. Verify with `GET http://127.0.0.1:8090/health`.

`POST /analyze` accepts a multipart field named `image`. `POST /cluster` accepts
JSON face IDs and embeddings. Laravel stores embeddings and crops privately;
neither service endpoint should be exposed to gallery visitors.
