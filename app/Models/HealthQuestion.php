<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthQuestion extends Model
{
    protected $fillable = [
        'sort_order',
        'question_text',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function answers(): HasMany
    {
        return $this->hasMany(HealthQuestionAnswer::class, 'question_id')
            ->orderBy('sort_order');
    }
}