<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Jobs\ExpireBookingJob;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Slot;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /** Buat booking baru — slot dikunci, timer 30 menit dimulai (FR-C3, FR-C4) */
    public function store(Request $request)
    {
        $request->validate([
            'slot_id' => ['required', 'exists:slots,id'],
        ]);

        $slot = Slot::with('lapangan')->findOrFail($request->slot_id);

        abort_unless($slot->status === 'tersedia', 422, 'Slot tidak tersedia.');
        abort_unless($slot->lapangan->status_approval === 'approved', 404);

        return DB::transaction(function () use ($slot) {
            $isOpenMatch = $slot->tipe === 'open_match';
            $totalHarga  = $isOpenMatch ? $slot->hargaPerKursi() : $slot->harga;

            $booking = Booking::create([
                'slot_id'            => $slot->id,
                'user_id'            => Auth::id(),
                'tipe_booking'       => $slot->tipe,
                'jumlah_kursi'       => 1,
                'total_harga'        => $totalHarga,
                'status'             => Booking::STATUS_MENUNGGU_PEMBAYARAN,
                'waktu_booking'      => now(),
                'batas_waktu_bayar'  => now()->addMinutes(30),
            ]);

            // Update status slot (biasa → dibooking, open_match → tetap tersedia sampai penuh)
            if (!$isOpenMatch) {
                $slot->update(['status' => 'dibooking']);
            }

            // Kirim notifikasi ke Owner
            NotifikasiService::kirim(
                $slot->lapangan->owner_id,
                'Booking Baru',
                "Ada booking baru untuk lapangan \"{$slot->lapangan->nama}\" pada {$slot->tanggal->format('d M Y')} pukul {$slot->jam_mulai}.",
                'booking_baru',
                $booking->id
            );

            // Auto-expire setelah 30 menit jika bukti belum diupload (FR-C5)
            ExpireBookingJob::dispatch($booking->id)->delay(now()->addMinutes(30));

            return redirect()->route('player.booking.payment', $booking)
                ->with('success', 'Booking berhasil dibuat. Silakan upload bukti transfer.');
        });
    }

    /** Halaman upload bukti transfer */
    public function payment(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);
        abort_unless($booking->status === Booking::STATUS_MENUNGGU_PEMBAYARAN, 422, 'Status booking tidak valid.');

        $booking->load('slot.lapangan');

        return view('player.booking-payment', compact('booking'));
    }

    /** Upload bukti transfer — status → menunggu_verifikasi (FR-C6) */
    public function uploadBukti(Request $request, Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);
        abort_unless($booking->status === Booking::STATUS_MENUNGGU_PEMBAYARAN, 422, 'Status booking tidak valid.');

        // Cek apakah sudah expired
        if (now()->gt($booking->batas_waktu_bayar)) {
            $booking->update(['status' => Booking::STATUS_EXPIRED]);
            return redirect()->route('player.history')
                ->with('error', 'Batas waktu upload sudah habis. Booking dibatalkan.');
        }

        $request->validate([
            'bukti_transfer' => ['required', 'image', 'max:2048'],
        ]);

        $path = $request->file('bukti_transfer')->store('bukti_transfer', 'public');

        Pembayaran::create([
            'booking_id'     => $booking->id,
            'bukti_transfer' => $path,
            'waktu_upload'   => now(),
        ]);

        $booking->update(['status' => Booking::STATUS_MENUNGGU_VERIFIKASI]);

        return redirect()->route('player.history')
            ->with('success', 'Bukti transfer berhasil diunggah. Menunggu verifikasi Owner.');
    }

    /** Riwayat booking user */
    public function history()
    {
        $bookings = Booking::with(['slot.lapangan.cabangOlahraga', 'pembayaran', 'rating'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('player.history', compact('bookings'));
    }
}
