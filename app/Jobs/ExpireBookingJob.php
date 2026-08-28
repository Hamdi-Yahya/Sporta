<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Services\NotifikasiService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ExpireBookingJob implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $bookingId) {}

    /** Auto-expire booking jika bukti transfer belum diupload (FR-C5, §2.6) */
    public function handle(): void
    {
        $booking = Booking::find($this->bookingId);

        if (!$booking || $booking->status !== Booking::STATUS_MENUNGGU_PEMBAYARAN) {
            return;
        }

        $booking->update(['status' => Booking::STATUS_EXPIRED]);

        // Buka kembali slot jika booking biasa
        if ($booking->tipe_booking === 'biasa') {
            $booking->slot->update(['status' => 'tersedia']);
        }

        NotifikasiService::kirim(
            $booking->user_id,
            'Booking Expired',
            'Booking Anda dibatalkan karena bukti transfer tidak diunggah dalam batas waktu.',
            'booking_expired',
            $booking->id
        );
    }
}
