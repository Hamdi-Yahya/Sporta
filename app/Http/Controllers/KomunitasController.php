<?php

namespace App\Http\Controllers;

use App\Models\Komunitas;
use App\Models\PesanChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomunitasController extends Controller
{
    /** Daftar 7 ruang chat komunitas per cabor (FR-F1) */
    public function index(Request $request)
    {
        $query = Komunitas::with('cabangOlahraga')->withCount('pesanChats');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('cabangOlahraga', function($q2) use ($search) {
                    $q2->where('nama_cabor', 'like', '%' . $search . '%');
                })->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $komunitasList = $query->get();

        return view('player.community', compact('komunitasList'));
    }

    /** Tampilkan ruang chat + riwayat pesan (FR-F2, FR-F4) */
    public function show(Request $request, Komunitas $komunitas)
    {
        $komunitas->load('cabangOlahraga');

        $pesanList = PesanChat::with('user')
            ->where('komunitas_id', $komunitas->id)
            ->orderBy('waktu_kirim', 'asc')
            ->take(100)
            ->get();

        $query = Komunitas::with('cabangOlahraga');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('cabangOlahraga', function($q2) use ($search) {
                    $q2->where('nama_cabor', 'like', '%' . $search . '%');
                })->orWhere('deskripsi', 'like', '%' . $search . '%');
            });
        }

        $komunitasList = $query->get();

        return view('player.community-chat', compact('komunitas', 'pesanList', 'komunitasList'));
    }

    /** Kirim pesan teks ke ruang chat (FR-F3, FR-F5) */
    public function kirimPesan(Request $request, Komunitas $komunitas)
    {
        $request->validate([
            'isi_pesan' => ['required', 'string', 'max:1000'],
        ]);

        $pesan = PesanChat::create([
            'komunitas_id' => $komunitas->id,
            'user_id'      => Auth::id(),
            'isi_pesan'    => $request->isi_pesan,
            'waktu_kirim'  => now(),
        ]);

        // Broadcast via Reverb saat Modul 8 diaktifkan
        event(new \App\Events\PesanTerkirim($pesan));

        if ($request->wantsJson()) {
            return response()->json([
                'pesan' => $pesan->load('user'),
            ]);
        }

        return back();
    }
}
