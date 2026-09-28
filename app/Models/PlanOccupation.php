<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanOccupation extends Model
{
    protected $fillable = [
        'plan_id',
        'occupation_en',
        'occupation_th',
        'occupation_slug',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}