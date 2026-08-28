<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\CabangOlahraga;
use App\Models\Lapangan;
use App\Services\NotifikasiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LapanganController extends Controller
{
    /** Daftar lapangan milik Owner yang login */
    public function index()
    {
        $lapangans = Lapangan::with('cabangOlahraga')
            ->where('owner_id', Auth::id())
            ->latest()
            ->get();

        return view('owner.fields', compact('lapangans'));
    }

    /** Form registrasi lapangan baru (FR-B1) */
    public function create()
    {
        $caborList = CabangOlahraga::bookable()->get();
        return view('owner.fields-create', compact('caborList'));
    }

    /** Simpan lapangan baru — status default pending, tunggu approval Admin */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'cabor_id'   => ['required', 'exists:cabang_olahragas,id'],
            'lokasi'     => ['required', 'string', 'max:255'],
            'deskripsi'  => ['nullable', 'string'],
            'fasilitas'  => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['owner_id']        = Auth::id();
        $validated['status_approval'] = 'pending';

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        Lapangan::create($validated);

        return redirect()->route('owner.fields')
            ->with('success', 'Lapangan berhasil didaftarkan. Menunggu persetujuan Admin.');
    }

    /** Form edit lapangan */
    public function edit(Lapangan $lapangan)
    {
        abort_unless($lapangan->owner_id === Auth::id(), 403);
        $caborList = CabangOlahraga::bookable()->get();
        return view('owner.fields-edit', compact('lapangan', 'caborList'));
    }

    /** Update data lapangan */
    public function update(Request $request, Lapangan $lapangan)
    {
        abort_unless($lapangan->owner_id === Auth::id(), 403);

        $validated = $request->validate([
            'nama'       => ['required', 'string', 'max:255'],
            'cabor_id'   => ['required', 'exists:cabang_olahragas,id'],
            'lokasi'     => ['required', 'string', 'max:255'],
            'deskripsi'  => ['nullable', 'string'],
            'fasilitas'  => ['nullable', 'string'],
            'foto'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            if ($lapangan->foto) {
                Storage::disk('public')->delete($lapangan->foto);
            }
            $validated['foto'] = $request->file('foto')->store('lapangan', 'public');
        }

        $lapangan->update($validated);

        return redirect()->route('owner.fields')
            ->with('success', 'Data lapangan berhasil diperbarui.');
    }

    /** Hapus lapangan */
    public function destroy(Lapangan $lapangan)
    {
        abort_unless($lapangan->owner_id === Auth::id(), 403);

        if ($lapangan->foto) {
            Storage::disk('public')->delete($lapangan->foto);
        }
        $lapangan->delete();

        return redirect()->route('owner.fields')
            ->with('success', 'Lapangan berhasil dihapus.');
    }
}
