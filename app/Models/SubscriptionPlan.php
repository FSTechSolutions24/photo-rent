<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'price', 'billing_cycle', 'is_popular', 'available_from', 'available_to', 'most_popular', 'storage_gb'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'most_popular' => 'boolean',
        'available_from' => 'datetime',
        'available_to' => 'datetime',
    ];

    /** Only plans that require payment; the free trial is never renewable. */
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('price', '>', 0);
    }

    public function lines()
    {
        return $this->hasMany(SubscriptionPlanLine::class);
    }
}
