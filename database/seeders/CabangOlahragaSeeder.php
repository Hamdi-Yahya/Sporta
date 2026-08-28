<?php

namespace Database\Seeders;

use App\Models\CabangOlahraga;
use App\Models\Komunitas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CabangOlahragaSeeder extends Seeder
{
    /** Seed 7 cabor sesuai §1.3.1 + auto-generate ruang komunitas per cabor */
    public function run(): void
    {
        $caborList = [
            ['nama_cabor' => 'Futsal',       'dapat_dibooking' => true],
            ['nama_cabor' => 'Bulu Tangkis',  'dapat_dibooking' => true],
            ['nama_cabor' => 'Basket',        'dapat_dibooking' => true],
            ['nama_cabor' => 'Tenis',         'dapat_dibooking' => true],
            ['nama_cabor' => 'Padel',         'dapat_dibooking' => true],
            ['nama_cabor' => 'Mini Soccer',   'dapat_dibooking' => true],
            ['nama_cabor' => 'Lari',          'dapat_dibooking' => false],
        ];

        foreach ($caborList as $item) {
            $cabor = CabangOlahraga::updateOrCreate(
                ['nama_cabor' => $item['nama_cabor']],
                [
                    'slug'           => Str::slug($item['nama_cabor']),
                    'dapat_dibooking' => $item['dapat_dibooking'],
                ]
            );

            Komunitas::updateOrCreate(
                ['cabor_id' => $cabor->id],
                [
                    'nama'      => 'Komunitas ' . $item['nama_cabor'],
                    'deskripsi' => 'Ruang diskusi dan koordinasi untuk pecinta ' . $item['nama_cabor'] . ' di Kota Pekalongan.',
                ]
            );
        }
    }
}
