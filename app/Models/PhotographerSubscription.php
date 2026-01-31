<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotographerSubscription extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'photographer_id', 'plan_id', 'subscription_type', 'start_date', 'end_date'];
}
