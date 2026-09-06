<?php

namespace App\Contracts;

interface FaceAnalysisProvider
{
    /**
     * Analyze one JPEG and return all usable faces in a single response.
     *
     * @return array{model_version:string,width:int,height:int,faces:array<int,array<string,mixed>>}
     */
    public function analyze(string $jpeg, string $filename): array;
}
