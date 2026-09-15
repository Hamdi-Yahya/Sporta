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

        $status = $request->input('status', Booking::STATUS_MENUNGGU_VERIFIKASI);

        $bookings = Booking::with(['slot.lapangan', 'user', 'pembayaran'])
            ->whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
            ->where('status', $status)
            ->latest()
            ->paginate(15);

        $pendingCount = Booking::whereHas('slot', fn($q) => $q->whereIn('lapangan_id', $lapanganIds))
            ->where('status', Booking::STATUS_MENUNGGU_VERIFIKASI)
            ->count();

        return view('owner.verify-booking', compact('bookings', 'pendingCount', 'status'));
    }

    /** Setujui booking — status → terkonfirmasi (FR-D3) */
    public function approve(Booking $booking)
    {
        $this->authorizeOwner($booking);
        abort_unless($booking->status === Booking::STATUS_MENUNGGU_VERIFIKASI, 422);

        $booking->update(['status' => Booking::STATUS_TERKONFIRMASI]);

        // Update kuota jika open_match dan cek apakah penuh (FR-E4)
        if ($booking->tipe_booking === 'open_match') {
            $slot = $booking->slot;
            $confirmedCount = Booking::where('slot_id', $slot->id)
                ->where('status', Booking::STATUS_TERKONFIRMASI)
                ->sum('jumlah_kursi');

            $slot->update(['kuota_terisi' => $confirmedCount]);

            if ($slot->isKuotaPenuh()) {
                $slot->update(['status' => 'penuh']);

                // Notif ke seluruh peserta yang sudah join (FR-G1)
                $pesertaIds = Booking::where('slot_id', $slot->id)
                    ->where('status', Booking::STATUS_TERKONFIRMASI)
                    ->pluck('user_id');

                foreach ($pesertaIds as $userId) {
                    NotifikasiService::kirim(
                        $userId,
                        'Slot Open Match Penuh!',
                        "Slot open match di \"{$slot->lapangan->nama}\" pada {$slot->tanggal->format('d M Y')} sudah penuh. Sampai jumpa di lapangan!",
                        'open_match_penuh',
                        $slot->id
                    );
                }
            }
        }

        // Notif ke User: booking disetujui (FR-D5)
        NotifikasiService::kirim(
            $booking->user_id,
            'Booking Disetujui',
            "Booking Anda untuk \"{$booking->slot->lapangan->nama}\" pada {$booking->slot->tanggal->format('d M Y')} telah dikonfirmasi.",
            'booking_approved',
            $booking->id
        );

        return redirect()->route('owner.verify-booking')
            ->with('success', 'Booking berhasil disetujui.');
    }

    /** Tolak booking — status → ditolak, slot dibuka kembali (FR-D4) */
    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeOwner($booking);
        abort_unless($booking->status === Booking::STATUS_MENUNGGU_VERIFIKASI, 422);

        $request->validate(['alasan' => ['required', 'string', 'max:500']]);

        $booking->update(['status' => Booking::STATUS_DITOLAK]);

        // Simpan catatan reject di pembayaran
        if ($booking->pembayaran) {
            $booking->pembayaran->update(['catatan_reject' => $request->alasan]);
        }

        // Buka kembali slot jika booking biasa
        if ($booking->tipe_booking === 'biasa') {
            $booking->slot->update(['status' => 'tersedia']);
        }

        // Notif ke User: booking ditolak (FR-D5)
        NotifikasiService::kirim(
            $booking->user_id,
            'Booking Ditolak',
            "Booking Anda ditolak. Alasan: {$request->alasan}",
            'booking_rejected',
            $booking->id
        );

        return redirect()->route('owner.verify-booking')
            ->with('success', 'Booking ditolak.');
    }

    /** Validasi kepemilikan — hanya Owner dari lapangan terkait yang bisa verifikasi */
    private function authorizeOwner(Booking $booking): void
    {
        $booking->load('slot.lapangan');
        abort_unless($booking->slot->lapangan->owner_id === Auth::id(), 403);
    }
}
