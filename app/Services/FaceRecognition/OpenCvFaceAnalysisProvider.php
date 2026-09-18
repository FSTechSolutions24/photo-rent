<?php

namespace App\Services\FaceRecognition;

use App\Contracts\FaceAnalysisProvider;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class OpenCvFaceAnalysisProvider implements FaceAnalysisProvider
{
    public function analyze(string $jpeg, string $filename): array
    {
        $response = $this->request()
            ->attach('image', $jpeg, $filename, ['Content-Type' => 'image/jpeg'])
            ->post(config('face-recognition.url').'/analyze');

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Face analysis service returned HTTP %s: %s',
                $response->status(),
                mb_substr($response->body(), 0, 1000)
            ));
        }

        $result = $response->json();
        $this->validateResult($result);

        return $result;
    }

    public function cluster(array $faces): array
    {
        $response = $this->request()->post(config('face-recognition.url').'/cluster', [
            'faces' => $faces,
        ]);

        if (! $response->successful()) {
            throw new RuntimeException(sprintf(
                'Face clustering service returned HTTP %s: %s',
                $response->status(),
                mb_substr($response->body(), 0, 1000)
            ));
        }

        $result = $response->json();
        $this->validateClusterResult($result, array_column($faces, 'id'));

        return $result;
    }

    private function request(): PendingRequest
    {
        $request = Http::acceptJson()
            ->withOptions(['connect_timeout' => config('face-recognition.connect_timeout')])
            ->timeout(config('face-recognition.timeout'));

        if (config('face-recognition.api_key')) {
            $request = $request->withToken(config('face-recognition.api_key'));
        }

        return $request;
    }

    private function validateResult($result): void
    {
        if (! is_array($result)
            || ! is_string($result['model_version'] ?? null)
            || ! is_numeric($result['width'] ?? null)
            || ! is_numeric($result['height'] ?? null)
            || ! is_array($result['faces'] ?? null)) {
            throw new RuntimeException('Face analysis service returned an invalid response.');
        }

        foreach ($result['faces'] as $face) {
            $box = $face['bbox'] ?? null;
            if (! is_array($face)
                || ! is_array($box)
                || count($box) !== 4
                || count(array_filter($box, 'is_numeric')) !== 4
                || ! is_numeric($face['confidence'] ?? null)
                || ! is_array($face['embedding'] ?? null)
                || empty($face['embedding'])) {
                throw new RuntimeException('Face analysis service returned an invalid face record.');
            }
        }
    }

    private function validateClusterResult($result, array $allowedFaceIds): void
    {
        if (! is_array($result)
            || ! is_string($result['model_version'] ?? null)
            || ! is_array($result['clusters'] ?? null)) {
            throw new RuntimeException('Face clustering service returned an invalid response.');
        }

        $allowed = array_fill_keys(array_map('strval', $allowedFaceIds), true);
        $seen = [];

        foreach ($result['clusters'] as $cluster) {
            $faceIds = $cluster['face_ids'] ?? null;
            $representativeId = (string) ($cluster['representative_face_id'] ?? '');
            if (! is_array($cluster) || ! is_array($faceIds) || empty($faceIds)) {
                throw new RuntimeException('Face clustering service returned an invalid cluster.');
            }

            $faceIds = array_map('strval', $faceIds);
            if (! in_array($representativeId, $faceIds, true)) {
                throw new RuntimeException('A representative face is not part of its cluster.');
            }

            foreach ($faceIds as $faceId) {
                if (! isset($allowed[$faceId]) || isset($seen[$faceId])) {
                    throw new RuntimeException('Face clustering returned an unknown or duplicate face ID.');
                }
                $seen[$faceId] = true;
            }
        }

        if (count($seen) !== count($allowed)) {
            throw new RuntimeException('Face clustering did not return every submitted face.');
        }
    }
}
