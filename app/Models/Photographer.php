<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photographer extends Model
{
    use HasFactory, Notifiable;

    public const TRIAL_DAYS = 14;
    public const TRIAL_STORAGE_BYTES = 8 * 1024 * 1024 * 1024;

    protected $fillable = ['user_id', 'subdomain', 'plan_storage', 'available_storage', 'payment_order_id', 'active', 'is_trial', 'trial_started_at', 'trial_ends_at'];

    protected $casts = [
        'active' => 'boolean',
        'is_trial' => 'boolean',
        'trial_started_at' => 'datetime',
        'trial_ends_at' => 'datetime',
    ];

    public function expireTrialIfNeeded()
    {
        if ($this->is_trial && $this->active && $this->trial_ends_at && $this->trial_ends_at->lte(Carbon::now())) {
            $this->update(['active' => false]);
            return true;
        }

        return false;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
