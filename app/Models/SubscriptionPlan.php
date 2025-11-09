<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'billing_cycle', 'is_popular', 'available_from', 'available_to'];

    public function lines()
    {
        return $this->hasMany(SubscriptionPlanLine::class);
    }
}
