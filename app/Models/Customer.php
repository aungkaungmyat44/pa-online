<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
        'email',
        'is_otp_sent',
        'otp_code',
        'otp_expires_at',
        'otp_attempts',
        'otp_verified_at',
        'is_activated',
    ];
}