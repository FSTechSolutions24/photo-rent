<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanLine extends Model
{
    use HasFactory;

    protected $fillable = ['subscription_plan_id', 'feature_name', 'description', 'is_included'];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
