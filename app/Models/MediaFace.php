<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaFace extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id', 'gallery_face_cluster_id', 'face_index',
        'box_x', 'box_y', 'box_width', 'box_height',
        'detection_confidence', 'quality_score', 'embedding',
        'crop_disk', 'crop_path', 'model_version',
    ];

    protected $casts = [
        'embedding' => 'array',
        'box_x' => 'float',
        'box_y' => 'float',
        'box_width' => 'float',
        'box_height' => 'float',
        'detection_confidence' => 'float',
        'quality_score' => 'float',
    ];

    protected $hidden = ['embedding', 'crop_path'];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function cluster()
    {
        return $this->belongsTo(GalleryFaceCluster::class, 'gallery_face_cluster_id');
    }
}
