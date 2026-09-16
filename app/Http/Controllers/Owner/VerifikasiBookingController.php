<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiBookingController extends Controller
{
    /** Daftar booking untuk lapangan milik Owner (bisa difilter by status) (FR-D2) */
    public function index(Request $request)
    {
        $lapanganIds = Lapangan::where('owner_id', Auth::id())->pluck('id');

        $status = $request->input('status', Booking::STATUS_MENUNGGU_PEMBAYARAN);

        $bookings = Booking::with(['slot.lapangan', 'user', 'pembayaran'])
            ->whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
            ->where('status', $status)
            ->latest()
            ->paginate(15);

        $pendingCount = Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
            ->where('status', Booking::STATUS_MENUNGGU_PEMBAYARAN)
            ->count();

        return view('owner.verify-booking', compact('bookings', 'pendingCount', 'status'));
    }



    /** Validasi kepemilikan — hanya Owner dari lapangan terkait yang bisa verifikasi */
    private function authorizeOwner(Booking $booking): void
    {
        $booking->load('slot.lapangan');
        abort_unless($booking->slot->lapangan->owner_id === Auth::id(), 403);
    }
}
