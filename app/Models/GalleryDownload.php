<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryDownload extends Model
{
    use HasFactory;

    protected $fillable = ['gallery_id', 'folder_id', 'selected_folder_ids', 'user_type', 'requested_by_email', 'full_gallery', 'url', 'status', 'size', 'expires_at'];

    protected $casts = [
        'selected_folder_ids' => 'array',
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
