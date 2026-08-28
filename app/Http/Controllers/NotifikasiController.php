<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /** Daftar notifikasi user (FR-G1) */
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('player.notifikasi', compact('notifikasis'));
    }

    /** Tandai notifikasi sebagai sudah dibaca */
    public function markAsRead(Notifikasi $notifikasi)
    {
        abort_unless($notifikasi->user_id === Auth::id(), 403);

        $notifikasi->update(['status_baca' => true]);

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    /** Jumlah notifikasi belum dibaca — untuk badge bell icon */
    public function unreadCount()
    {
        $count = Notifikasi::where('user_id', Auth::id())
            ->belumDibaca()
            ->count();

        return response()->json(['count' => $count]);
    }
}
