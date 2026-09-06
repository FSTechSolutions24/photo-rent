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
}
