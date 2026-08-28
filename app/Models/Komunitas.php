<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Komunitas extends Model
{
    protected $table = 'komunitas';

    protected $fillable = ['cabor_id', 'nama', 'deskripsi'];

    public function cabangOlahraga(): BelongsTo
    {
        return $this->belongsTo(CabangOlahraga::class, 'cabor_id');
    }

    public function pesanChats(): HasMany
    {
        return $this->hasMany(PesanChat::class);
    }
}
