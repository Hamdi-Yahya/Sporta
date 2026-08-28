<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\User;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_user'      => User::where('role', 'user')->count(),
            'total_owner'     => User::where('role', 'owner')->count(),
            'total_lapangan'  => Lapangan::count(),
            'pending_approval'=> Lapangan::pending()->count(),
            'total_booking'   => Booking::count(),
        ];

        $pendingLapangans = Lapangan::with(['owner', 'cabangOlahraga'])
            ->pending()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingLapangans'));
    }
}
