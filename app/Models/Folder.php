<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['gallery_id', 'name', 'thumbnail_path'];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
