<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $owner = Auth::user();
        $lapanganIds = Lapangan::where('owner_id', $owner->id)->pluck('id');

        $stats = [
            'total_lapangan'  => $lapanganIds->count(),
            'menunggu_verifikasi' => Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
                                    ->where('status', 'menunggu_verifikasi')->count(),
            'pendapatan_bulan_ini' => Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
                                    ->whereIn('status', ['terkonfirmasi', 'selesai'])
                                    ->whereMonth('waktu_booking', now()->month)
                                    ->whereYear('waktu_booking', now()->year)
                                    ->sum('total_harga'),
            'booking_bulan_ini' => Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
                                    ->whereMonth('waktu_booking', now()->month)
                                    ->whereYear('waktu_booking', now()->year)
                                    ->count(),
            'rating_rata'     => \App\Models\Rating::whereIn('lapangan_id', $lapanganIds)->avg('skor') ?? 0,
        ];

        $recentBookings = Booking::with(['slot.lapangan', 'user'])
            ->whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
            ->latest()
            ->take(5)
            ->get();

        $todaySlots = \App\Models\Slot::with('lapangan')
            ->whereIn('lapangan_id', $lapanganIds)
            ->whereDate('tanggal', now()->toDateString())
            ->orderBy('jam_mulai')
            ->take(6)
            ->get();

        return view('owner.dashboard', compact('stats', 'recentBookings', 'todaySlots'));
    }
}
