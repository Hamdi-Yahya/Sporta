<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Player\BookingSearchController;
use App\Http\Controllers\Player\BookingController;
use App\Http\Controllers\Player\OpenMatchController;
use App\Http\Controllers\Player\RatingController;
use App\Http\Controllers\Owner\LapanganController;
use App\Http\Controllers\Owner\SlotController;
use App\Http\Controllers\Owner\VerifikasiBookingController;
use App\Http\Controllers\Owner\BookingOfflineController;
use App\Http\Controllers\Admin\LapanganApprovalController;
use App\Http\Controllers\KomunitasController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\Api\MidtransWebhookController;
use App\Http\Controllers\Player\DashboardController as PlayerDashboardController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

/*
|──────────────────────────────────────────────────────────────────────────────
| SPORTA — Web Routes
|──────────────────────────────────────────────────────────────────────────────
*/

/* ─── Public ────────────────────────────────────────────────── */
Route::get('/', function () {
    /* Lapangan populer (section hero bawah) */
    $popularFields = \App\Models\Lapangan::with('cabangOlahraga')
        ->approved()
        ->orderByDesc('rating_rata2')
        ->orderByDesc('jumlah_ulasan')
        ->take(6)
        ->get()
        ->map(function ($lapangan) {
            return [
                'name'        => $lapangan->nama,
                'sport'       => $lapangan->cabangOlahraga->nama_cabor,
                'type'        => str_contains(strtolower($lapangan->fasilitas), 'indoor') ? 'Indoor' : 'Outdoor',
                'location'    => $lapangan->lokasi,
                'price'       => number_format($lapangan->slots()->min('harga') ?? 50000, 0, ',', '.'),
                'rating'      => $lapangan->rating_rata2,
                'reviewCount' => $lapangan->jumlah_ulasan,
                'href'        => '/player/booking/lapangan/' . $lapangan->id,
                'image'       => $lapangan->foto ? asset('storage/' . $lapangan->foto) : null,
            ];
        });

    /* Booking terkonfirmasi terbaru — untuk hero card */
    $latestBooking = \App\Models\Booking::with(['slot.lapangan'])
        ->where('status', \App\Models\Booking::STATUS_TERKONFIRMASI)
        ->latest()
        ->first();

    /* Open Match aktif terbaru — untuk hero card */
    $latestOpenMatch = \App\Models\Slot::with('lapangan')
        ->openMatch()
        ->tersedia()
        ->whereDate('tanggal', '>=', now()->toDateString())
        ->orderBy('tanggal')
        ->first();

    return view('welcome', compact('popularFields', 'latestBooking', 'latestOpenMatch'));
})->name('home');

/* ─── Auth (Guest Only) ─────────────────────────────────────── */
Route::middleware('guest')->group(function () {
    Route::get('/login',     [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login',    [LoginController::class, 'login']);
    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/* ─── Dashboard Player / User ────────────────────────────────── */
Route::prefix('player')->name('player.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [PlayerDashboardController::class, 'index'])->name('dashboard');

    // Pencarian & Booking (FR-C1–C6)
    Route::get('/booking',              [BookingSearchController::class, 'index'])->name('booking');
    Route::get('/booking/search',       [BookingSearchController::class, 'search'])->name('booking.search');
    Route::get('/booking/lapangan/{lapangan}', [BookingSearchController::class, 'show'])->name('booking.detail');
    Route::post('/booking/store',       [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{booking}/payment', [BookingController::class, 'payment'])->name('booking.payment');

    // Open Match (FR-E1–E5)
    Route::get('/open-match',           [OpenMatchController::class, 'index'])->name('open-match');
    Route::post('/open-match/book',     [OpenMatchController::class, 'book'])->name('open-match.book');

    // Komunitas (FR-F1–F6) — akses User
    Route::get('/community',            [KomunitasController::class, 'index'])->name('community');
    Route::get('/community/{komunitas}',[KomunitasController::class, 'show'])->name('community.show');
    Route::post('/community/{komunitas}/kirim', [KomunitasController::class, 'kirimPesan'])->name('community.kirim');

    // Riwayat & Rating (FR-I1–I3)
    Route::get('/history',              [BookingController::class, 'history'])->name('history');
    Route::post('/rating',              [RatingController::class, 'store'])->name('rating.store');

    // Profil & Notifikasi
    Route::get('/profile',              fn() => view('player.profile'))->name('profile');
    Route::get('/notifikasi',           [NotifikasiController::class, 'index'])->name('notifikasi');
});

/* ─── Dashboard Owner ────────────────────────────────────────── */
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

    // Manajemen Lapangan (FR-B1, B3, B6)
    Route::get('/fields',               [LapanganController::class, 'index'])->name('fields');
    Route::get('/fields/create',        [LapanganController::class, 'create'])->name('fields.create');
    Route::post('/fields',              [LapanganController::class, 'store'])->name('fields.store');
    Route::get('/fields/{lapangan}/edit', [LapanganController::class, 'edit'])->name('fields.edit');
    Route::put('/fields/{lapangan}',    [LapanganController::class, 'update'])->name('fields.update');
    Route::delete('/fields/{lapangan}', [LapanganController::class, 'destroy'])->name('fields.destroy');

    // Kelola Jadwal & Slot (FR-B4, B5, B6)
    Route::get('/schedules',            [SlotController::class, 'index'])->name('schedules');
    Route::post('/schedules',           [SlotController::class, 'store'])->name('schedules.store');
    Route::put('/schedules/{slot}',     [SlotController::class, 'update'])->name('schedules.update');
    Route::delete('/schedules/{slot}',  [SlotController::class, 'destroy'])->name('schedules.destroy');

    // Kelola Booking Offline
    Route::get('/booking-offline',      [BookingOfflineController::class, 'index'])->name('booking-offline');
    Route::post('/booking-offline',     [BookingOfflineController::class, 'store'])->name('booking-offline.store');

    // Verifikasi Booking (FR-D1–D5)
    Route::get('/verify-booking',       [VerifikasiBookingController::class, 'index'])->name('verify-booking');

    // Komunitas (akses sama dengan User, §3.5)
    Route::get('/community',            [KomunitasController::class, 'index'])->name('community');
    Route::get('/community/{komunitas}',[KomunitasController::class, 'show'])->name('community.show');
    Route::post('/community/{komunitas}/kirim', [KomunitasController::class, 'kirimPesan'])->name('community.kirim');

    // Profil Usaha
    Route::get('/profile',              fn() => view('owner.profile', ['user' => auth()->user()]))->name('profile');
    Route::get('/notifikasi',           [NotifikasiController::class, 'index'])->name('notifikasi');
});

/* ─── Admin Panel (FR-H1, H2) ────────────────────────────────── */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard',            [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/approval',             [LapanganApprovalController::class, 'index'])->name('approval');
    Route::post('/approval/{lapangan}/approve', [LapanganApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{lapangan}/reject',  [LapanganApprovalController::class, 'reject'])->name('approval.reject');
});

/* ─── Notifikasi API (shared) ────────────────────────────────── */
Route::middleware('auth')->group(function () {
    Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markAsRead'])->name('notifikasi.read');
    Route::get('/notifikasi/unread-count',       [NotifikasiController::class, 'unreadCount'])->name('notifikasi.unread-count');
});

/* ─── Midtrans Webhook ───────────────────────────────────────── */
Route::post('/api/midtrans/notification', [MidtransWebhookController::class, 'handle'])->name('midtrans.notification');
