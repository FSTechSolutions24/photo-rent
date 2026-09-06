<?php

return [
    'enabled' => env('FACE_RECOGNITION_ENABLED', false),
    'provider' => env('FACE_RECOGNITION_PROVIDER', 'opencv'),
    'queue' => env('FACE_RECOGNITION_QUEUE', 'faces'),
    'url' => rtrim(env('FACE_RECOGNITION_URL', 'http://127.0.0.1:8090'), '/'),
    'api_key' => env('FACE_RECOGNITION_API_KEY'),
    'timeout' => (int) env('FACE_RECOGNITION_TIMEOUT', 60),
    'connect_timeout' => (int) env('FACE_RECOGNITION_CONNECT_TIMEOUT', 5),
    'model_version' => env('FACE_RECOGNITION_MODEL_VERSION', 'opencv-yunet-2023mar+sface-2021dec'),
    'analysis_max_dimension' => (int) env('FACE_RECOGNITION_ANALYSIS_MAX_DIMENSION', 2000),
    'analysis_jpeg_quality' => (int) env('FACE_RECOGNITION_ANALYSIS_JPEG_QUALITY', 82),
    'crop_size' => (int) env('FACE_RECOGNITION_CROP_SIZE', 256),
    'crop_padding' => (float) env('FACE_RECOGNITION_CROP_PADDING', 0.18),
    'storage_disk' => env('FACE_RECOGNITION_STORAGE_DISK', 'wasabi'),
];
