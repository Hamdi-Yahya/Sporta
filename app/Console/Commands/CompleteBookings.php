<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CompleteBookings extends Command
{
    protected $signature = 'booking:complete';
    protected $description = 'Tandai booking terkonfirmasi yang jadwalnya sudah berlalu sebagai selesai';

    /** Transisi terkonfirmasi → selesai jika jadwal sudah lewat */
    public function handle(): void
    {
        $completed = Booking::where('status', Booking::STATUS_TERKONFIRMASI)
            ->whereHas('slot', function ($q) {
                $q->where(function ($sub) {
                    $sub->where('tanggal', '<', now()->toDateString())
                        ->orWhere(function ($sub2) {
                            $sub2->where('tanggal', '=', now()->toDateString())
                                 ->whereRaw("jam_selesai <= ?", [now()->format('H:i:s')]);
                        });
                });
            })
            ->get();

        foreach ($completed as $booking) {
            $booking->update(['status' => Booking::STATUS_SELESAI]);
            $booking->slot->update(['status' => 'selesai']);
        }

        $this->info("Completed {$completed->count()} booking(s).");
    }
}
