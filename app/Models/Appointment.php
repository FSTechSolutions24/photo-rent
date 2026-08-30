<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['photographer_id', 'session_id', 'name', 'description', 'date', 'start_time', 'end_time'];

    public function getStartTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getEndTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function photographer()
    {
        return $this->belongsTo(Photographer::class);
    }
}
