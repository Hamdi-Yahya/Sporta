<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $fillable = [
        'booking_id', 'bukti_transfer', 'waktu_upload', 'catatan_reject',
    ];

    protected function casts(): array
    {
        return ['waktu_upload' => 'datetime'];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
