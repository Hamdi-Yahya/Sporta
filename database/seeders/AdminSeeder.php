<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /** Buat akun admin default untuk platform */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sporta.id'],
            [
                'name'     => 'Admin SPORTA',
                'password' => 'password',
                'role'     => 'admin',
                'no_telp'  => '08123456789',
            ]
        );
    }
}
