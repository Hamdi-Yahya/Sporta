<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lapangan extends Model
{
    protected $fillable = [
        'owner_id', 'cabor_id', 'nama', 'lokasi',
        'deskripsi', 'fasilitas', 'foto',
        'status_approval', 'alasan_reject',
        'rating_rata2', 'jumlah_ulasan',
    ];

    protected function casts(): array
    {
        return [
            'rating_rata2' => 'decimal:2',
            'jumlah_ulasan' => 'integer',
        ];
    }

    /* ─── Scopes ─────────────────────────────────────────── */

    /** Hanya lapangan yang sudah disetujui Admin */
    public function scopeApproved($query)
    {
        return $query->where('status_approval', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status_approval', 'pending');
    }

    /* ─── Relasi ─────────────────────────────────────────── */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function cabangOlahraga(): BelongsTo
    {
        return $this->belongsTo(CabangOlahraga::class, 'cabor_id');
    }

    public function slots(): HasMany
    {
        return $this->hasMany(Slot::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /* ─── Helper: Recalculate rating setelah review baru ── */
    public function recalculateRating(): void
    {
        $this->rating_rata2   = $this->ratings()->avg('skor') ?? 0;
        $this->jumlah_ulasan  = $this->ratings()->count();
        $this->save();
    }
}
