<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    protected $fillable = ['photographer_id', 'name', 'slug', 'thumbnail_path', 'client_password', 'guest_password', 'is_public'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }
}
