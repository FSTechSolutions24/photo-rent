<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionFinance extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'name', 'description', 'credit_debit', 'amount', 'date'];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }
}
