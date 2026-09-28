<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
        'email',
    ];

    protected $hidden = [
        'otp_code',
    ];

    protected function casts(): array
    {
        return [
            'is_otp_sent' => 'boolean',
            'otp_expires_at' => 'datetime',
            'otp_attempts' => 'integer',
            'otp_verified_at' => 'datetime',
            'is_activated' => 'boolean',
        ];
    }
}