<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = ['folder_id', 'gallery_id', 'path', 'name', 'disk', 'size', 'meta'];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
