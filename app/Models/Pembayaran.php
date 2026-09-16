<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $fillable = [
        'booking_id', 'order_id', 'transaction_id', 'payment_type',
        'gross_amount', 'transaction_status', 'fraud_status',
        'snap_token', 'paid_at', 'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'gross_amount' => 'integer',
            'paid_at'      => 'datetime',
            'expired_at'   => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
