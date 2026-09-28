<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name_en',
        'name_th',
        'death_coverage',
        'assaulted_coverage',
        'vehicle_coverage',
        'medical_expense_coverage',
        'premium_amount',
    ];

    protected function casts(): array
    {
        return [
            'death_coverage' => 'decimal:2',
            'assaulted_coverage' => 'decimal:2',
            'vehicle_coverage' => 'decimal:2',
            'medical_expense_coverage' => 'decimal:2',
            'premium_amount' => 'decimal:2',
        ];
    }
}