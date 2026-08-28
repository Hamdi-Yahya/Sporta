<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanChat extends Model
{
    protected $fillable = ['komunitas_id', 'user_id', 'isi_pesan', 'waktu_kirim'];

    protected function casts(): array
    {
        return ['waktu_kirim' => 'datetime'];
    }

    public function komunitas(): BelongsTo
    {
        return $this->belongsTo(Komunitas::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
