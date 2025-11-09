<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlanLine extends Model
{
    use HasFactory;

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
