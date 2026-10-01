<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingRegistration extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'email',
        'phone',
        'password',
        'otp_hash',
        'otp_expires_at',
        'otp_attempts',
        'otp_send_count',
        'otp_last_sent_at',
        'registration_ip',
    ];

    protected $hidden = [
        'password',
        'otp_hash',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
        'otp_last_sent_at' => 'datetime',
        'otp_attempts' => 'integer',
        'otp_send_count' => 'integer',
    ];

    public function getMaskedPhoneAttribute(): string
    {
        return substr($this->phone, 0, 4).' ••• ••• '.substr($this->phone, -3);
    }
}
