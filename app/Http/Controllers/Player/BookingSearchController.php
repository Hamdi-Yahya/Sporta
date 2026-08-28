<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\CabangOlahraga;
use App\Models\Lapangan;
use App\Models\Slot;
use Illuminate\Http\Request;

class BookingSearchController extends Controller
{
    /** Halaman pencarian lapangan dengan filter (FR-C1) */
    public function index()
    {
        $caborList = CabangOlahraga::bookable()->get();

        $lapangans = Lapangan::with('cabangOlahraga')
            ->approved()
            ->latest()
            ->paginate(12);

        return view('player.booking-search', compact('caborList', 'lapangans'));
    }

    /** Pencarian dengan filter — cabor, lokasi, harga (FR-C1) */
    public function search(Request $request)
    {
        $caborList = CabangOlahraga::bookable()->get();

        $query = Lapangan::with('cabangOlahraga')->approved();

        if ($request->filled('cabor_id')) {
            $query->where('cabor_id', $request->cabor_id);
        }

        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        if ($request->filled('harga_min') || $request->filled('harga_max')) {
            $query->whereHas('slots', function ($q) use ($request) {
                if ($request->filled('harga_min')) {
                    $q->where('harga', '>=', $request->harga_min);
                }
                if ($request->filled('harga_max')) {
                    $q->where('harga', '<=', $request->harga_max);
                }
            });
        }

        $lapangans = $query->paginate(12)->appends($request->query());

        return view('player.booking-search', compact('caborList', 'lapangans'));
    }

    /** Detail lapangan + slot yang tersedia (FR-C2) */
    public function show(Lapangan $lapangan)
    {
        abort_unless($lapangan->status_approval === 'approved', 404);

        $lapangan->load(['cabangOlahraga', 'ratings.user']);

        $slots = Slot::where('lapangan_id', $lapangan->id)
            ->where('tanggal', '>=', now()->toDateString())
            ->whereIn('status', ['tersedia', 'penuh'])
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->get();

        return view('player.booking-detail', compact('lapangan', 'slots'));
    }
}
