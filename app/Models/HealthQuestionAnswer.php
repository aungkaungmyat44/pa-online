<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthQuestionAnswer extends Model
{
    protected $fillable = [
        'question_id',
        'sort_order',
        'answer_text',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(HealthQuestion::class, 'question_id');
    }
}