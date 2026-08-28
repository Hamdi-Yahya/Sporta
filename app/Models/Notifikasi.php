<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $fillable = [
        'user_id', 'judul', 'isi', 'tipe', 'reference_id', 'status_baca',
    ];

    protected function casts(): array
    {
        return ['status_baca' => 'boolean'];
    }

    /** Scope: hanya notifikasi yang belum dibaca */
    public function scopeBelumDibaca($query)
    {
        return $query->where('status_baca', false);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
