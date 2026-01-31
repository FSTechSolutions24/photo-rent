<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photographer extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ['user_id', 'subdomain', 'plan_storage', 'available_storage', 'payment_order_id', 'active'];

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
