<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Services\NotifikasiService;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'booking:expire';
    protected $description = 'Safety net — expire booking yang melewati batas waktu bayar (fallback untuk job queue)';

    /** Cari booking menunggu_pembayaran yang sudah melewati batas waktu */
    public function handle(): void
    {
        $expired = Booking::where('status', Booking::STATUS_MENUNGGU_PEMBAYARAN)
            ->where('batas_waktu_bayar', '<', now())
            ->get();

        foreach ($expired as $booking) {
            $booking->update(['status' => Booking::STATUS_EXPIRED]);

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

        $this->info("Expired {$expired->count()} booking(s).");
    }
}
