<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\CabangOlahraga;
use App\Models\Komunitas;
use App\Models\Lapangan;
use App\Models\Slot;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tambahkan Admin Seeder
        $this->call(AdminSeeder::class);

        // 2. Seeding CabangOlahraga & Komunitas
        $sports = [
            ["id" => 1, "name" => "Futsal", "bookable" => true, "open_match" => true, "community" => true],
            ["id" => 2, "name" => "Bulu Tangkis", "bookable" => true, "open_match" => true, "community" => true],
            ["id" => 3, "name" => "Basket", "bookable" => true, "open_match" => true, "community" => true],
            ["id" => 4, "name" => "Tenis", "bookable" => true, "open_match" => true, "community" => true],
            ["id" => 5, "name" => "Padel", "bookable" => true, "open_match" => true, "community" => true],
            ["id" => 6, "name" => "Mini Soccer", "bookable" => true, "open_match" => true, "community" => true],
            ["id" => 7, "name" => "Lari", "bookable" => false, "open_match" => false, "community" => true]
        ];

        foreach ($sports as $sport) {
            $cabor = CabangOlahraga::updateOrCreate(
                ['id' => $sport['id']],
                [
                    'nama_cabor'      => $sport['name'],
                    'slug'            => Str::slug($sport['name']),
                    'dapat_dibooking' => $sport['bookable']
                ]
            );

            if ($sport['community']) {
                Komunitas::updateOrCreate(
                    ['cabor_id' => $cabor->id],
                    [
                        'nama'      => 'Komunitas ' . $sport['name'],
                        'deskripsi' => 'Ruang diskusi dan koordinasi untuk pecinta ' . $sport['name'] . ' di Kota Pekalongan.'
                    ]
                );
            }
        }

        // 3. Seeding Users (Players)
        $users = [
            ["id" => 1, "name" => "Muhammad Rizky Pratama", "phone" => "081234560101", "email" => "rizky@example.com"],
            ["id" => 2, "name" => "Fajar Ramadhan", "phone" => "081234560102", "email" => "fajar@example.com"],
            ["id" => 3, "name" => "Ardi Maulana", "phone" => "081234560103", "email" => "ardi@example.com"],
            ["id" => 4, "name" => "Dimas Saputra", "phone" => "081234560104", "email" => "dimas@example.com"],
            ["id" => 5, "name" => "Naufal Akbar", "phone" => "081234560105", "email" => "naufal@example.com"],
            ["id" => 6, "name" => "Reza Fadillah", "phone" => "081234560106", "email" => "reza@example.com"],
            ["id" => 7, "name" => "Bagas Ramadhan", "phone" => "081234560107", "email" => "bagas@example.com"],
            ["id" => 8, "name" => "Aditya Prakoso", "phone" => "081234560108", "email" => "aditya@example.com"],
            ["id" => 9, "name" => "Farhan Maulana", "phone" => "081234560109", "email" => "farhan@example.com"],
            ["id" => 10, "name" => "Ilham Setiawan", "phone" => "081234560110", "email" => "ilham@example.com"],
            ["id" => 11, "name" => "Kevin Alvaro", "phone" => "081234560111", "email" => "kevin@example.com"],
            ["id" => 12, "name" => "Yoga Pratama", "phone" => "081234560112", "email" => "yoga@example.com"]
        ];

        foreach ($users as $u) {
            User::firstOrCreate(
                ['email' => $u['email']],
                [
                    'name'     => $u['name'],
                    'no_telp'  => $u['phone'],
                    'password' => Hash::make('Sporta123!'),
                    'role'     => 'user'
                ]
            );
        }

        // 4. Seeding Owners
        $owners = [
            ["name" => "Raka Haryanto", "phone" => "081235670201", "email" => "owner.profutsal@example.com", "business_name" => "FUTSAL PRO PEKALONGAN"],
            ["name" => "Andi Prasetyo", "phone" => "081235670202", "email" => "owner.citragarden@example.com", "business_name" => "Fitcentrum CitraGarden Pekalongan"],
            ["name" => "Fajar Ramadhan", "phone" => "081235670203", "email" => "owner.kauman@example.com", "business_name" => "Kauman Badminton Arena"],
            ["name" => "Dimas Saputra", "phone" => "081235670204", "email" => "owner.smartvenue@example.com", "business_name" => "Smart Venue Badminton Court and Cafe"],
            ["name" => "Wahyu Setiawan", "phone" => "081235670205", "email" => "owner.gorjetayu@example.com", "business_name" => "Lapangan Basket GOR Jetayu"],
            ["name" => "Andika Nugroho", "phone" => "081235670206", "email" => "owner.citragardenbasket@example.com", "business_name" => "Fitcentrum CitraGarden Pekalongan"],
            ["name" => "Dwi Kurniawan", "phone" => "081235670207", "email" => "owner.tenispembangunan@example.com", "business_name" => "Lapangan Tenis Pembangunan"],
            ["name" => "Tegar Firmansyah", "phone" => "081235670208", "email" => "owner.tenisjetayu@example.com", "business_name" => "Lapangan Tenis Jetayu"],
            ["name" => "Fikri Ramadhan", "phone" => "081235670209", "email" => "owner.padel99@example.com", "business_name" => "Padel Court 99"],
            ["name" => "Reza Mahendra", "phone" => "081235670210", "email" => "owner.dupanpadel@example.com", "business_name" => "Dupan Padel Club Pekalongan"],
            ["name" => "Arman Setyawan", "phone" => "081235670211", "email" => "owner.minisoccer1@example.com", "business_name" => "Lapangan Mini Soccer A"],
            ["name" => "Rendi Pratama", "phone" => "081235670212", "email" => "owner.minisoccer2@example.com", "business_name" => "Lapangan Mini Soccer B"]
        ];

        // Memetakan email owner ke id untuk venues
        $ownerIds = [];
        foreach ($owners as $idx => $o) {
            $user = User::firstOrCreate(
                ['email' => $o['email']],
                [
                    'name'       => $o['name'],
                    'no_telp'    => $o['phone'],
                    'password'   => Hash::make('Sporta123!'),
                    'role'       => 'owner',
                    'nama_usaha' => $o['business_name']
                ]
            );
            $ownerIds[$idx + 1] = $user->id; // ID 1-12 sesuai index json owner
        }

        // 5. Seeding Venues / Lapangans
        $venues = [
            ["id" => 1, "owner_id" => 1, "sport_id" => 1, "name" => "FUTSAL PRO PEKALONGAN", "address" => "Jl. Kenanga No.33B, Klego, Pekalongan Timur, Kota Pekalongan", "price_from" => 50000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan vinyl", "Futsal academy", "Parkir", "Toilet"], "approval_status" => "approved", "rating" => 4.8, "review_count" => 0],
            ["id" => 2, "owner_id" => 2, "sport_id" => 1, "name" => "Fitcentrum CitraGarden Pekalongan", "address" => "Jl. Panjang Baru, Pekalongan Utara, Kota Pekalongan", "price_from" => 30000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan semi-indoor", "Parkir", "Ruang ganti", "Shower", "Toilet"], "approval_status" => "approved", "rating" => 4.6, "review_count" => 0],
            ["id" => 3, "owner_id" => 3, "sport_id" => 2, "name" => "Kauman Badminton Arena", "address" => "Jl. Semarang No.15, Kauman, Pekalongan Timur, Kota Pekalongan", "price_from" => 50000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan vinyl", "Tipe lantai Sand", "Latihan", "Private lesson"], "approval_status" => "approved", "rating" => 4.7, "review_count" => 0],
            ["id" => 4, "owner_id" => 4, "sport_id" => 2, "name" => "Smart Venue Badminton Court and Cafe", "address" => "Jl. Muria No.6, Bendan, Pekalongan Barat, Kota Pekalongan", "price_from" => 50000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan karpet", "Cafe", "Parkir", "Toilet"], "approval_status" => "approved", "rating" => 4.6, "review_count" => 0],
            ["id" => 5, "owner_id" => 5, "sport_id" => 3, "name" => "Lapangan Basket GOR Jetayu", "address" => "Jl. Jetayu, Panjang Wetan, Pekalongan Utara, Kota Pekalongan", "price_from" => 140000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan basket", "Fasilitas GOR", "Parkir", "Toilet"], "approval_status" => "approved", "rating" => 4.5, "review_count" => 0],
            ["id" => 6, "owner_id" => 6, "sport_id" => 3, "name" => "Fitcentrum CitraGarden Pekalongan", "address" => "Jl. Panjang Baru, Pekalongan Utara, Kota Pekalongan", "price_from" => 30000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan basket outdoor", "Parkir", "Ruang ganti", "Shower", "Toilet"], "approval_status" => "approved", "rating" => 4.5, "review_count" => 0],
            ["id" => 7, "owner_id" => 7, "sport_id" => 4, "name" => "Lapangan Tenis Pembangunan", "address" => "Jl. Pembangunan, Kota Pekalongan", "price_from" => 40000, "operating_hours" => "06:00-23:59", "facilities" => ["Lapangan tenis", "Fasilitas olahraga pemerintah"], "approval_status" => "approved", "rating" => 4.5, "review_count" => 0],
            ["id" => 8, "owner_id" => 8, "sport_id" => 4, "name" => "Lapangan Tenis Jetayu", "address" => "Jl. Jetayu, Panjang Wetan, Pekalongan Utara, Kota Pekalongan", "price_from" => 40000, "operating_hours" => "06:00-23:59", "facilities" => ["Lapangan tenis outdoor", "Lapangan tenis indoor", "Fasilitas GOR"], "approval_status" => "approved", "rating" => 4.6, "review_count" => 0],
            ["id" => 9, "owner_id" => 9, "sport_id" => 5, "name" => "Padel Court 99", "address" => "Jl. H. Bachtiar Amin, Seruni Utara, Krapyak, Pekalongan", "price_from" => 99000, "operating_hours" => "07:00-23:00", "facilities" => ["Panoramic court", "Cafe", "Restoran", "Shower", "Mushola", "Parkir"], "approval_status" => "approved", "rating" => 4.8, "review_count" => 0],
            ["id" => 10, "owner_id" => 10, "sport_id" => 5, "name" => "Dupan Padel Club Pekalongan", "address" => "Jl. Dr. Sutomo, Baros, Pekalongan Timur, Komplek Dupan Square", "price_from" => 150000, "operating_hours" => "07:00-23:00", "facilities" => ["Lapangan padel", "Shower", "Toilet", "Mushola", "Parkir"], "approval_status" => "approved", "rating" => 4.7, "review_count" => 0],
            // Mini Soccer A & B (Pending status) - set default price & facilities
            ["id" => 11, "owner_id" => 11, "sport_id" => 6, "name" => "Lapangan Mini Soccer A", "address" => "Kota Pekalongan", "price_from" => 100000, "operating_hours" => "07:00-23:00", "facilities" => ["Parkir", "Toilet", "Kantin"], "approval_status" => "pending", "rating" => 0, "review_count" => 0, "data_status" => "perlu_verifikasi"],
            ["id" => 12, "owner_id" => 12, "sport_id" => 6, "name" => "Lapangan Mini Soccer B", "address" => "Kota Pekalongan", "price_from" => 100000, "operating_hours" => "07:00-23:00", "facilities" => ["Parkir", "Toilet", "Ruang Ganti"], "approval_status" => "pending", "rating" => 0, "review_count" => 0, "data_status" => "perlu_verifikasi"]
        ];

        $lapanganModels = [];

        foreach ($venues as $v) {
            $l = Lapangan::updateOrCreate(
                ['id' => $v['id']],
                [
                    'owner_id'        => $ownerIds[$v['owner_id']],
                    'cabor_id'        => $v['sport_id'],
                    'nama'            => $v['name'],
                    'lokasi'          => $v['address'],
                    'deskripsi'       => 'Buka jam: ' . ($v['operating_hours'] ?? '07:00-23:00'),
                    'fasilitas'       => json_encode($v['facilities']),
                    'status_approval' => $v['approval_status'],
                    'rating_rata2'    => $v['rating'],
                    'jumlah_ulasan'   => $v['review_count'],
                ]
            );
            
            // Simpan referensi ke array untuk dipakai di seeder slot
            if ($v['approval_status'] === 'approved') {
                $lapanganModels[] = [
                    'model' => $l,
                    'price' => $v['price_from'] ?: 100000
                ];
            }
        }

        // 6. Seeding Slots dummy untuk beberapa lapangan yang approved (untuk demo)
        // Kita ambil 4 lapangan pertama yang approved
        $demoLapangans = array_slice($lapanganModels, 0, 4);
        
        $today = Carbon::now();
        // Buat slot untuk 3 hari ke depan
        for ($i = 0; $i < 3; $i++) {
            $date = $today->copy()->addDays($i)->format('Y-m-d');
            
            foreach ($demoLapangans as $dl) {
                $l = $dl['model'];
                $basePrice = $dl['price'];

                // Bikin 5 slot untuk setiap lapangan
                $jamMulai = [8, 10, 14, 16, 19];
                
                foreach ($jamMulai as $idx => $jam) {
                    $jm = sprintf('%02d:00:00', $jam);
                    $js = sprintf('%02d:00:00', $jam + 1);
                    
                    // Slot sore/malam dibuat sedikit lebih mahal
                    $price = $jam >= 15 ? $basePrice + 20000 : $basePrice;

                    // Buat selang-seling biasa dan open match
                    $isOM = ($idx % 2 !== 0 && $l->cabangOlahraga->nama_cabor !== 'Lari');
                    
                    // Random status
                    $statuses = ['tersedia', 'dibooking', 'tersedia', 'tersedia'];
                    $status = $statuses[array_rand($statuses)];
                    
                    Slot::create([
                        'lapangan_id'  => $l->id,
                        'tanggal'      => $date,
                        'jam_mulai'    => $jm,
                        'jam_selesai'  => $js,
                        'tipe'         => $isOM ? 'open_match' : 'biasa',
                        'harga'        => $price, // Simpan harga total, karena Model Slot akan membaginya otomatis dengan kuota_total di hargaPerKursi()
                        'kuota_total'  => $isOM ? 10 : null,
                        'kuota_terisi' => $isOM ? rand(0, 8) : 0,
                        'status'       => $status,
                    ]);
                }
            }
        }
    }
}
