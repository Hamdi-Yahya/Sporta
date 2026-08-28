<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Slot extends Model
{
    protected $fillable = [
        'lapangan_id', 'tanggal', 'jam_mulai', 'jam_selesai',
        'tipe', 'harga', 'kuota_total', 'kuota_terisi', 'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'      => 'date',
            'harga'        => 'integer',
            'kuota_total'  => 'integer',
            'kuota_terisi' => 'integer',
        ];
    }

    /* ─── Scopes ─────────────────────────────────────────── */

    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeOpenMatch($query)
    {
        return $query->where('tipe', 'open_match');
    }

    /* ─── Helper ─────────────────────────────────────────── */

    /** Harga per kursi untuk slot Open Match */
    public function hargaPerKursi(): int
    {
        if ($this->tipe !== 'open_match' || !$this->kuota_total) {
            return $this->harga;
        }
        return (int) ceil($this->harga / $this->kuota_total);
    }

    /** Cek apakah kuota open match sudah penuh */
    public function isKuotaPenuh(): bool
    {
        return $this->tipe === 'open_match'
            && $this->kuota_terisi >= $this->kuota_total;
    }

    /* ─── Relasi ─────────────────────────────────────────── */

    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
