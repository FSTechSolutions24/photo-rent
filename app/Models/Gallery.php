<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    use HasFactory;

    public const LAYOUT_MASONRY = 'masonry';
    public const LAYOUT_EDITORIAL = 'editorial';
    public const LAYOUT_LUXE = 'luxe';

    protected $fillable = [
        'photographer_id', 'session_id', 'name', 'slug', 'thumbnail_path', 'background_path',
        'gallery_layout', 'client_password', 'guest_password', 'is_public',
        'face_processing_enabled', 'face_filter_published', 'face_processing_status',
        'face_processing_error', 'faces_clustered_at',
    ];

    protected $casts = [
        'face_processing_enabled' => 'boolean',
        'face_filter_published' => 'boolean',
        'faces_clustered_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function (Gallery $gallery) {
            if (! Schema::hasTable('media_faces')) {
                return;
            }

            Storage::disk(config('face-recognition.storage_disk'))
                ->deleteDirectory("face-data/galleries/{$gallery->id}");
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function faceClusters()
    {
        return $this->hasMany(GalleryFaceCluster::class);
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->galleryAssetUrl($this->thumbnail_path);
    }

    public function getBackgroundUrlAttribute()
    {
        return $this->galleryAssetUrl($this->background_path);
    }

    private function galleryAssetUrl($path)
    {
        if (! $path) {
            return null;
        }

        // Gallery assets uploaded before Wasabi support remain available locally.
        if (! str_starts_with($path, 'users/')) {
            return '/storage/' . ltrim($path, '/');
        }

        return Storage::disk('wasabi')->temporaryUrl($path, now()->addDays(7));
    }
}
