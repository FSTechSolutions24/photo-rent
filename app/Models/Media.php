<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'folder_id', 'gallery_id', 'path', 'name', 'disk', 'size', 'meta', 'private',
        'face_analysis_status', 'face_analysis_checksum', 'face_analysis_model_version',
        'face_analysis_error', 'face_analyzed_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'face_analyzed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function (Media $media) {
            if (! Schema::hasTable('media_faces')) {
                return;
            }

            foreach ($media->faces()->get(['crop_disk', 'crop_path']) as $face) {
                Storage::disk($face->crop_disk)->delete($face->crop_path);
            }
        });
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function faces()
    {
        return $this->hasMany(MediaFace::class);
    }
}
