<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Jobs\ExpireBookingJob;
use App\Models\Booking;
use App\Models\Slot;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OpenMatchController extends Controller
{
    /** Daftar slot Open Match yang tersedia lintas lapangan (FR-E1) */
    public function index()
    {
        $slots = Slot::with('lapangan.cabangOlahraga')
            ->openMatch()
            ->where('status', 'tersedia')
            ->where('tanggal', '>=', now()->toDateString())
            ->orderBy('tanggal')
            ->orderBy('jam_mulai')
            ->paginate(12);

        return view('player.open-match', compact('slots'));
    }

    /** Booking 1 kursi pada slot Open Match (FR-E2, FR-E3) */
    public function book(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:slots,id'],
        ]);

        $slot = Slot::with('lapangan')->findOrFail($request->slot_id);

        abort_unless($slot->tipe === 'open_match', 422, 'Slot ini bukan Open Match.');
        abort_unless($slot->status === 'tersedia', 422, 'Slot sudah penuh atau tidak tersedia.');
        abort_unless($slot->lapangan->status_approval === 'approved', 404);

        // Hitung kursi yang sudah dibooking (termasuk yang belum dikonfirmasi)
        $bookedKursi = Booking::where('slot_id', $slot->id)
            ->whereNotIn('status', [Booking::STATUS_EXPIRED, Booking::STATUS_DITOLAK])
            ->sum('jumlah_kursi');

        abort_unless($bookedKursi < $slot->kuota_total, 422, 'Kuota sudah penuh.');

        return DB::transaction(function () use ($slot) {
            $hargaPerKursi = $slot->hargaPerKursi();

            $booking = Booking::create([
                'slot_id'           => $slot->id,
                'user_id'           => Auth::id(),
                'tipe_booking'      => 'open_match',
                'jumlah_kursi'      => 1,
                'total_harga'       => $hargaPerKursi,
                'status'            => Booking::STATUS_MENUNGGU_PEMBAYARAN,
                'waktu_booking'     => now(),
                'batas_waktu_bayar' => now()->addMinutes(30),
            ]);

            // Notif ke Owner
            NotifikasiService::kirim(
                $slot->lapangan->owner_id,
                'Booking Open Match Baru',
                "Ada pemain baru yang bergabung ke slot open match \"{$slot->lapangan->nama}\" ({$slot->tanggal->format('d M Y')} {$slot->jam_mulai}).",
                'booking_baru',
                $booking->id
            );

            // Auto-expire 30 menit (FR-C5)
            ExpireBookingJob::dispatch($booking->id)->delay(now()->addMinutes(30));

            return redirect()->route('player.booking.payment', $booking)
                ->with('success', 'Booking Open Match berhasil. Silakan upload bukti transfer.');
        });
    }
}
