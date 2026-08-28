<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'total_booking'   => Booking::where('user_id', $user->id)->count(),
            'aktif'           => Booking::where('user_id', $user->id)->where('status', 'terkonfirmasi')->count(),
            'menunggu'        => Booking::where('user_id', $user->id)->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi'])->count(),
            'selesai'         => Booking::where('user_id', $user->id)->where('status', 'selesai')->count(),
        ];

        $recentBookings = Booking::with('slot.lapangan')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('player.dashboard', compact('stats', 'recentBookings'));
    }
}
