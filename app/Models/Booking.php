<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'slot_id', 'user_id', 'tipe_booking', 'jumlah_kursi',
        'total_harga', 'status', 'waktu_booking', 'batas_waktu_bayar',
    ];

    protected function casts(): array
    {
        return [
            'total_harga'       => 'integer',
            'jumlah_kursi'      => 'integer',
            'waktu_booking'     => 'datetime',
            'batas_waktu_bayar' => 'datetime',
        ];
    }

    /* ─── Status Constants (§2.6 State Machine) ──────────── */

    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    const STATUS_MENUNGGU_VERIFIKASI = 'menunggu_verifikasi';
    const STATUS_TERKONFIRMASI       = 'terkonfirmasi';
    const STATUS_DITOLAK             = 'ditolak';
    const STATUS_EXPIRED             = 'expired';
    const STATUS_SELESAI             = 'selesai';

    /* ─── Relasi ─────────────────────────────────────────── */

    public function slot(): BelongsTo
    {
        return $this->belongsTo(Slot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function rating(): HasOne
    {
        return $this->hasOne(Rating::class);
    }
}
