<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Models\Slot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SlotController extends Controller
{
    /** Daftar slot per lapangan milik Owner, difilter tanggal (FR-B4) */
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

            // Query slot berdasarkan lapangan DAN tanggal yang dipilih
            $slots = Slot::where('lapangan_id', $selectedLapangan->id)
                ->whereDate('tanggal', $selectedTanggal)
                ->orderBy('jam_mulai')
                ->get();
        }

        return view('owner.schedules', compact('lapangans', 'selectedLapangan', 'selectedTanggal', 'slots'));
    }

    /** Buat slot baru — biasa atau open_match (FR-B4, B5) */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lapangan_id'  => ['required', 'exists:lapangans,id'],
            'tanggal'      => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tipe'         => ['required', 'in:biasa,open_match'],
            'harga'        => ['required', 'integer', 'min:0'],
            'kuota_total'  => ['required_if:tipe,open_match', 'nullable', 'integer', 'min:2'],
        ]);

        // Pastikan lapangan milik Owner yang login
        $lapangan = Lapangan::where('owner_id', Auth::id())
            ->where('id', $validated['lapangan_id'])
            ->firstOrFail();

        $validated['status'] = 'tersedia';
        if ($validated['tipe'] === 'biasa') {
            $validated['kuota_total'] = null;
        }

        Slot::create($validated);

        return redirect()->route('owner.schedules', [
            'lapangan_id' => $lapangan->id,
            'tanggal'     => $validated['tanggal'],
        ])->with('success', 'Slot jadwal berhasil ditambahkan.');
    }

    /** Update slot yang belum dibooking (FR-B6) */
    public function update(Request $request, Slot $slot)
    {
        $lapangan = $slot->lapangan;
        abort_unless($lapangan->owner_id === Auth::id(), 403);
        abort_unless($slot->status === 'tersedia', 403, 'Slot yang sudah dibooking tidak dapat diubah.');

        $validated = $request->validate([
            'tanggal'      => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'    => ['required', 'date_format:H:i'],
            'jam_selesai'  => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'tipe'         => ['required', 'in:biasa,open_match'],
            'harga'        => ['required', 'integer', 'min:0'],
            'kuota_total'  => ['required_if:tipe,open_match', 'nullable', 'integer', 'min:2'],
        ]);

        if ($validated['tipe'] === 'biasa') {
            $validated['kuota_total'] = null;
            $validated['kuota_terisi'] = 0;
        }

        $slot->update($validated);

        return redirect()->route('owner.schedules', [
            'lapangan_id' => $lapangan->id,
            'tanggal'     => $validated['tanggal'],
        ])->with('success', 'Slot berhasil diperbarui.');
    }

    /** Hapus slot yang belum dibooking (FR-B6) */
    public function destroy(Slot $slot)
    {
        $lapangan = $slot->lapangan;
        $tanggal = $slot->tanggal->toDateString();
        abort_unless($lapangan->owner_id === Auth::id(), 403);
        abort_unless($slot->status === 'tersedia', 403, 'Slot yang sudah dibooking tidak dapat dihapus.');

        $slot->delete();

        return redirect()->route('owner.schedules', [
            'lapangan_id' => $lapangan->id,
            'tanggal'     => $tanggal,
        ])->with('success', 'Slot berhasil dihapus.');
    }
}
