<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /** Beri rating dan ulasan — hanya untuk booking berstatus selesai (FR-I1, FR-I3) */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id'  => ['required', 'exists:bookings,id'],
            'skor'        => ['required', 'integer', 'min:1', 'max:5'],
            'ulasan_teks' => ['nullable', 'string', 'max:500'],
        ]);

        $booking = Booking::with('slot.lapangan')->findOrFail($validated['booking_id']);

        abort_unless($booking->user_id === Auth::id(), 403);
        abort_unless($booking->status === Booking::STATUS_SELESAI, 422, 'Rating hanya bisa diberikan untuk booking yang sudah selesai.');

        // Cek apakah sudah pernah rating booking ini (FR-I3)
        if ($booking->rating) {
            return back()->with('error', 'Anda sudah memberikan rating untuk booking ini.');
        }

        Rating::create([
            'booking_id'  => $booking->id,
            'lapangan_id' => $booking->slot->lapangan_id,
            'user_id'     => Auth::id(),
            'skor'        => $validated['skor'],
            'ulasan_teks' => $validated['ulasan_teks'],
        ]);

        // Recalculate rata-rata rating lapangan (FR-I2)
        $booking->slot->lapangan->recalculateRating();

        return back()->with('success', 'Rating berhasil diberikan. Terima kasih!');
    }
}
