<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'order_unique_code',
        'plan_id',
        'customer_id',
        'customer_id_type',
        'customer_id_number',
        'agent_no',
        'policy_no',
        'order_info',
        'health_question_answers',
        'effective_date',
        'expire_date',
        'status',
        'premium_amount',
        'total_amount',
        'vat',
        'duty',
        'payment_method',
        'payment_status',
        'paid_at',
        'is_email_sent',
        'policy_url',
        'barcode_no',
        'is_policy_generated',
        'save_data_result',
        'issue_policy_result',
    ];

    protected function casts(): array
    {
        return [
            'order_info' => 'array',
            'health_question_answers' => 'array',
            'save_data_result' => 'array',
            'issue_policy_result' => 'array',
            'effective_date' => 'date',
            'expire_date' => 'date',
            'paid_at' => 'datetime',
            'premium_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'vat' => 'decimal:2',
            'duty' => 'decimal:2',
            'is_email_sent' => 'boolean',
            'is_policy_generated' => 'boolean',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function paymentTransitions(): HasMany
    {
        return $this->hasMany(PaymentTransition::class);
    }   
}