# Face recognition implementation

## Phase 1 (implemented)

- Laravel provider contract and authenticated HTTP OpenCV provider.
- Gallery, media-analysis, face, cluster, and private-thumbnail schema.
- Idempotent queued analysis keyed by the analysis JPEG checksum and model version.
- Correctly oriented, longest-edge-limited JPEG analysis payloads.
- One private 256 x 256 crop per detected face. The cluster phase will copy/select
  one of these real face crops as its representative thumbnail.
- FastAPI YuNet/SFace `/health`, `/analyze`, and cosine-DBSCAN `/cluster` service.
- Face data cleanup on normal gallery/media model deletion.

The global feature flag and each gallery's opt-in flag must both be enabled.
Gallery flags default off. The public filter is not part of this phase, and no
raw embeddings or private crop object keys should be serialized to visitors.

## Phase 2 (implemented)

- `ClusterGalleryFaces` calls `/cluster` with faces from one gallery only and
  validates that every returned ID belongs to that request.
- New groups start visible by default. A group explicitly hidden by the
  photographer remains hidden conservatively when memberships overlap.
- Representative face crops are copied to private cluster-thumbnail objects.
- The dashboard provides enable, publish, process, show, and hide controls.
- All three public gallery themes provide a responsive anonymous face row.
- Public filtering is performed with Eloquent relationship queries only.
- Dashboard and public thumbnail routes return short-lived private Wasabi URLs
  only after gallery access and ownership checks.

Still planned: manual merge, split, and representative-face selection tools.

## Safe activation checklist

1. Review (but do not yet execute) the two `2026_09_06_*` migrations.
2. Obtain the two model files named in `services/face-recognition/README.md`.
3. Build/start the service, then check `/health` locally.
4. Use a real asynchronous Laravel queue connection. A worker must consume both
   the existing default queue and the queue in `FACE_RECOGNITION_QUEUE`.
5. Run the migrations only after backing up the database.
6. Enable `FACE_RECOGNITION_ENABLED`, then opt in one disposable gallery for a
   small manual verification before enabling production galleries.
