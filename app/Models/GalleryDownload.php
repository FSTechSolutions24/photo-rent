<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryDownload extends Model
{
    use HasFactory;

    protected $fillable = ['gallery_id', 'folder_id', 'user_type', 'requested_by_email', 'full_gallery', 'url', 'status', 'size', 'expires_at'];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
