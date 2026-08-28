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
            'booking_masuk'   => Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
                                    ->where('status', 'menunggu_verifikasi')->count(),
            'terkonfirmasi'   => Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
                                    ->where('status', 'terkonfirmasi')->count(),
            'selesai'         => Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
                                    ->where('status', 'selesai')->count(),
        ];

        $recentBookings = Booking::with(['slot.lapangan', 'user'])
            ->whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact('stats', 'recentBookings'));
    }
}
