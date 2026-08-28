<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CabangOlahraga extends Model
{
    protected $fillable = ['nama_cabor', 'slug', 'dapat_dibooking'];

    protected function casts(): array
    {
        return ['dapat_dibooking' => 'boolean'];
    }

    /* ─── Scopes ─────────────────────────────────────────── */

    /** Hanya cabor yang mendukung booking lapangan */
    public function scopeBookable($query)
    {
        return $query->where('dapat_dibooking', true);
    }

    /* ─── Relasi ─────────────────────────────────────────── */

    public function lapangans(): HasMany
    {
        return $this->hasMany(Lapangan::class, 'cabor_id');
    }

    public function komunitas(): HasOne
    {
        return $this->hasOne(Komunitas::class, 'cabor_id');
    }
}
