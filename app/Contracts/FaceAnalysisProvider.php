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

    /**
     * Group face embeddings supplied from one gallery.
     *
     * @param array<int,array{id:string,embedding:array<int,float>,quality:?float}> $faces
     * @return array{model_version:string,clusters:array<int,array{face_ids:array<int,string>,representative_face_id:string}>}
     */
    public function cluster(array $faces): array;
}
