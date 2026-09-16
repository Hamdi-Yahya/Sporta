<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingOfflineController extends Controller
{
    /**
     * Tampilkan form untuk booking offline
     */
    public function index(Request $request)
    {
        $lapangans = Lapangan::where('owner_id', Auth::id())->approved()->get();

        $selectedLapangan = null;
        $selectedTanggal = $request->input('tanggal', now()->toDateString());
        $slots = collect();

        if ($request->filled('lapangan_id')) {
            $selectedLapangan = Lapangan::where('owner_id', Auth::id())
                ->where('id', $request->lapangan_id)
                ->firstOrFail();

            // Ambil slot yang masih tersedia
            $slots = Slot::where('lapangan_id', $selectedLapangan->id)
                ->whereDate('tanggal', $selectedTanggal)
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('owner.booking-offline.index', compact('lapangans', 'selectedLapangan', 'selectedTanggal', 'slots'));
    }

    /**
     * Simpan data booking offline
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slot_id'               => ['required', 'exists:slots,id'],
            'nama_pemesan_offline'  => ['required', 'string', 'max:255'],
            'no_hp_pemesan_offline' => ['nullable', 'string', 'max:20'],
            'catatan_offline'       => ['nullable', 'string'],
        ]);

        $slot = Slot::findOrFail($validated['slot_id']);
        $lapangan = $slot->lapangan;

        // Validasi kepemilikan lapangan
        abort_unless($lapangan->owner_id === Auth::id(), 403);

        // Validasi ketersediaan slot
        if ($slot->status !== 'tersedia') {
            return back()->with('error', 'Slot sudah terisi atau tidak tersedia.');
        }

        // Buat booking offline
        Booking::create([
            'slot_id'               => $slot->id,
            'user_id'               => null, // Offline user
            'sumber_booking'        => 'offline',
            'nama_pemesan_offline'  => $validated['nama_pemesan_offline'],
            'no_hp_pemesan_offline' => $validated['no_hp_pemesan_offline'],
            'catatan_offline'       => $validated['catatan_offline'],
            'tipe_booking'          => 'biasa', 
            'jumlah_kursi'          => $slot->tipe === 'open_match' ? $slot->kuota_total : 1,
            'total_harga'           => $slot->harga,
            'status'                => Booking::STATUS_TERKONFIRMASI,
            'waktu_booking'         => now(),
        ]);

        // Update status slot
        $slot->update([
            'status'       => 'dibooking',
            'kuota_terisi' => $slot->tipe === 'open_match' ? $slot->kuota_total : 0, 
        ]);

        return redirect()->route('owner.booking-offline', [
            'lapangan_id' => $lapangan->id,
            'tanggal'     => $slot->tanggal->toDateString(),
        ])->with('success', 'Booking Offline berhasil ditambahkan.');
    }
}
