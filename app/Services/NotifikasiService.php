<?php

namespace App\Services;

use App\Models\Notifikasi;

class NotifikasiService
{
    /** Buat notifikasi in-app untuk user tertentu (FR-G1) */
    public static function kirim(int $userId, string $judul, string $isi, ?string $tipe = null, ?int $referenceId = null): Notifikasi
    {
        return Notifikasi::create([
            'user_id'      => $userId,
            'judul'        => $judul,
            'isi'          => $isi,
            'tipe'         => $tipe,
            'reference_id' => $referenceId,
            'status_baca'  => false,
        ]);
    }
}
