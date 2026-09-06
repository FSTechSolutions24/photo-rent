<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryFaceCluster extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_VISIBLE = 'visible';
    public const STATUS_HIDDEN = 'hidden';

    protected $fillable = [
        'gallery_id', 'uuid', 'representative_media_face_id', 'face_count',
        'status', 'model_version', 'thumbnail_disk', 'thumbnail_path',
    ];

    protected $hidden = ['thumbnail_path'];

    protected static function booted()
    {
        static::creating(function (GalleryFaceCluster $cluster) {
            $cluster->uuid = $cluster->uuid ?: (string) Str::uuid();
        });

        static::deleting(function (GalleryFaceCluster $cluster) {
            if ($cluster->thumbnail_disk && $cluster->thumbnail_path) {
                Storage::disk($cluster->thumbnail_disk)->delete($cluster->thumbnail_path);
            }
        });
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function faces()
    {
        return $this->hasMany(MediaFace::class, 'gallery_face_cluster_id');
    }

    public function representativeFace()
    {
        return $this->belongsTo(MediaFace::class, 'representative_media_face_id');
    }
}
