<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'name', 'description', 'date', 'start_time', 'end_time'];

    public function session()
    {
        return $this->hasMany(Session::class);
    }
}
