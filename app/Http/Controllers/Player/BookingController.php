<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Jobs\ExpireBookingJob;
use App\Models\Booking;
use App\Models\Pembayaran;
use App\Models\Slot;
use App\Services\NotifikasiService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

            // Buat Pembayaran record dengan snap token (Midtrans)
            $orderId = 'SPORTA-' . $booking->id . '-' . Str::random(5);
            $midtransService = new MidtransService();
            $snapToken = $midtransService->getSnapToken($booking, $orderId);

            Pembayaran::create([
                'booking_id'         => $booking->id,
                'order_id'           => $orderId,
                'gross_amount'       => $totalHarga,
                'transaction_status' => 'pending',
                'snap_token'         => $snapToken,
            ]);

            // Kirim notifikasi ke Owner
            NotifikasiService::kirim(
                $slot->lapangan->owner_id,
                'Booking Baru',
                "Ada booking baru untuk lapangan \"{$slot->lapangan->nama}\" pada {$slot->tanggal->format('d M Y')} pukul {$slot->jam_mulai}. Menunggu pembayaran.",
                'booking_baru',
                $booking->id
            );

            // Auto-expire setelah 30 menit
            ExpireBookingJob::dispatch($booking->id)->delay(now()->addMinutes(30));

            return redirect()->route('player.booking.payment', $booking)
                ->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran.');
        });
    }

    /** Halaman upload bukti transfer */
    public function payment(Booking $booking)
    {
        abort_unless($booking->user_id === Auth::id(), 403);
        abort_unless($booking->status === Booking::STATUS_MENUNGGU_PEMBAYARAN, 422, 'Status booking tidak valid.');

        $booking->load(['slot.lapangan', 'pembayaran']);

        // Jika tidak ada token (misal karena error saat generate), kita bisa men-generate ulang di sini,
        // namun untuk sementara asumsikan token sudah ter-generate saat store().
        return view('player.booking-payment', compact('booking'));
    }

    /** Riwayat booking user — bisa difilter by status (FR-I1) */
    public function history(Request $request)
    {
        $query = Booking::with(['slot.lapangan.cabangOlahraga', 'pembayaran', 'rating'])
            ->where('user_id', Auth::id())
            ->latest();

        // Filter berdasarkan status tab
        $status = $request->get('status');
        if ($status === 'ditolak') {
            // Gabungkan ditolak + expired dalam satu tab
            $query->whereIn('status', [Booking::STATUS_DITOLAK, Booking::STATUS_EXPIRED]);
        } elseif ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->paginate(10);

        return view('player.history', compact('bookings'));
    }
}
