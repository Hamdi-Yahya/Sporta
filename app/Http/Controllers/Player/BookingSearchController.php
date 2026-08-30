<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\CabangOlahraga;
use App\Models\Lapangan;
use App\Models\Slot;
use Illuminate\Http\Request;

class BookingSearchController extends Controller
{
    /** Halaman utama — tampilkan semua lapangan (FR-C1) */
    public function index(Request $request)
    {
        $caborList = CabangOlahraga::bookable()->get();

        $query = Lapangan::with('cabangOlahraga')->approved();

        // Teruskan filter jika ada parameter di URL (dari form GET)
        $this->applyFilters($query, $request);

        $lapangans = $query->paginate(12)->appends($request->query());

        return view('player.booking-search', compact('caborList', 'lapangans'));
    }

    /** Pencarian dengan filter — semua parameter (FR-C1) */
    public function search(Request $request)
    {
        $caborList = CabangOlahraga::bookable()->get();

        $query = Lapangan::with('cabangOlahraga')->approved();

        $this->applyFilters($query, $request);

        $lapangans = $query->paginate(12)->appends($request->query());

        return view('player.booking-search', compact('caborList', 'lapangans'));
    }

    /** Terapkan semua filter ke query builder */
    private function applyFilters($query, Request $request): void
    {
        // Filter: nama lapangan
        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter: cabang olahraga (bisa multiple)
        if ($request->filled('cabor_id')) {
            $ids = (array) $request->cabor_id;
            $query->whereIn('cabor_id', $ids);
        }

        // Filter: lokasi
        if ($request->filled('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }

        // Filter: harga maksimum dari slot
        if ($request->filled('harga_max') && (int) $request->harga_max < 200000) {
            $query->whereHas('slots', function ($q) use ($request) {
                $q->where('harga', '<=', (int) $request->harga_max);
            });
        }

        // Filter: tipe lapangan (indoor/outdoor berdasarkan kolom fasilitas)
        if ($request->filled('type')) {
            $types = (array) $request->type;
            $query->where(function ($q) use ($types) {
                foreach ($types as $type) {
                    $q->orWhere('fasilitas', 'like', '%' . $type . '%');
                }
            });
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        if ($sort === 'rating') {
            $query->orderByDesc('rating_rata2')->orderByDesc('jumlah_ulasan');
        } elseif ($sort === 'harga_asc') {
            // Sort berdasarkan harga slot minimum
            $query->withMin('slots', 'harga')->orderBy('slots_min_harga');
        } elseif ($sort === 'harga_desc') {
            $query->withMin('slots', 'harga')->orderByDesc('slots_min_harga');
        } else {
            $query->latest();
        }
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

