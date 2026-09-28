<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransition extends Model
{
    protected $fillable = [
        'order_id',
        'customer_id',
        'reference_no',
        'charge_id',
        'qr_id',
        'link_ref',
        'provider',
        'method',
        'status',
        'provider_status',
        'amount',
        'currency',
        'payment_create_info',
        'inquiry_data_result',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_create_info' => 'array',
            'inquiry_data_result' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}