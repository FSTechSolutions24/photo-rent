<?php

namespace App\Models;

use App\Models\SessionFinance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'date', 'total_amount', 'client_id', 'photographer_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function finance()
    {
        return $this->hasMany(SessionFinance::class);
    }
}
