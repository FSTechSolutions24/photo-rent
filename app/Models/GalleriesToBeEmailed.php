<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleriesToBeEmailed extends Model
{
    use HasFactory;
    protected $fillable = ['gallery_downloads_id', 'send_to', 'sent_at', 'status'];

    public function gallery_download()
    {
        return $this->belongsTo(GalleryDownload::class, 'gallery_downloads_id', 'id');
    }
}
