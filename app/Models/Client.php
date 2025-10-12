<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['photographer_id', 'name', 'email', 'phone', 'phone2'];

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }
}
