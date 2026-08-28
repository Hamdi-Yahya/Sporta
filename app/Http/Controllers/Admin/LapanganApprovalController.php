<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lapangan;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;

class LapanganApprovalController extends Controller
{
    /** Daftar pengajuan lapangan pending (FR-H1) */
    public function index()
    {
        $pendingLapangans = Lapangan::with(['owner', 'cabangOlahraga'])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('admin.approval', compact('pendingLapangans'));
    }

    /** Setujui lapangan — status → approved (FR-H2, FR-B2) */
    public function approve(Lapangan $lapangan)
    {
        $lapangan->update(['status_approval' => 'approved', 'alasan_reject' => null]);

        NotifikasiService::kirim(
            $lapangan->owner_id,
            'Lapangan Disetujui',
            "Lapangan \"{$lapangan->nama}\" telah disetujui dan kini tayang di pencarian publik.",
            'lapangan_approved',
            $lapangan->id
        );

        return redirect()->route('admin.approval')
            ->with('success', "Lapangan \"{$lapangan->nama}\" berhasil disetujui.");
    }

    /** Tolak lapangan — status → rejected, simpan alasan (FR-H2, FR-B2) */
    public function reject(Request $request, Lapangan $lapangan)
    {
        $request->validate(['alasan_reject' => ['required', 'string', 'max:500']]);

        $lapangan->update([
            'status_approval' => 'rejected',
            'alasan_reject'   => $request->alasan_reject,
        ]);

        NotifikasiService::kirim(
            $lapangan->owner_id,
            'Lapangan Ditolak',
            "Lapangan \"{$lapangan->nama}\" ditolak. Alasan: {$request->alasan_reject}",
            'lapangan_rejected',
            $lapangan->id
        );

        return redirect()->route('admin.approval')
            ->with('success', "Lapangan \"{$lapangan->nama}\" ditolak.");
    }
}
