@extends('layouts.dashboard')

@section('title', 'Kelola Lapangan')
@section('page_title', 'Kelola Lapangan Saya')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

{{-- Flash message --}}
@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:6px;padding:12px 16px;margin-bottom:18px;display:flex;align-items:center;gap:10px;">
    <i data-lucide="check-circle" style="width:16px;height:16px;color:#16A34A;flex-shrink:0;"></i>
    <span style="font-size:0.875rem;color:#166534;">{{ session('success') }}</span>
</div>
@endif

{{-- ─── Header actions ─────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <p style="font-size:0.875rem;color:#64748B;margin:0;">
        Kelola semua lapangan yang kamu daftarkan di SPORTA.
    </p>
    <a href="{{ route('owner.fields.create') }}" class="btn-brand">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Daftarkan Lapangan Baru
    </a>
</div>

@php
    $approvedFields = $lapangans->where('status_approval', 'approved');
    $pendingFields  = $lapangans->where('status_approval', 'pending');
@endphp

{{-- ─── Lapangan yang sudah approved ──────────────────────── --}}
@if($approvedFields->count())
<h3 class="section-title" style="font-size:0.9375rem;margin-bottom:14px;">Lapangan Aktif</h3>
<div style="display:flex;flex-direction:column;gap:14px;margin-bottom:24px;">
    @foreach($approvedFields as $lapangan)
    <div class="card" style="padding:20px;">
        <div style="display:flex;align-items:flex-start;gap:18px;">
            {{-- Foto lapangan --}}
            <div style="width:120px;height:90px;border-radius:8px;background:#F1F5F9;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden;">
                @if($lapangan->foto)
                    <img src="{{ asset('storage/' . $lapangan->foto) }}" alt="Foto"
                         style="width:100%;height:100%;object-fit:cover;">
                @else
                    <i data-lucide="image" style="width:24px;height:24px;color:#94A3B8;"></i>
                @endif
            </div>

            <div style="flex:1;min-width:0;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px;gap:12px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;">
                            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;
                                       color:#1E293B;margin:0;">{{ $lapangan->nama }}</h3>
                            <span class="badge badge-success">
                                <i data-lucide="check-circle" style="width:10px;height:10px;"></i>
                                Disetujui Admin
                            </span>
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;display:flex;align-items:center;gap:5px;">
                            <i data-lucide="map-pin" style="width:12px;height:12px;"></i>
                            {{ $lapangan->lokasi }}
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-size:0.8125rem;color:#64748B;">
                            {{ $lapangan->cabangOlahraga->nama_cabor }}
                        </div>
                    </div>
                </div>

                @if($lapangan->fasilitas)
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px;">
                    @foreach(explode(',', $lapangan->fasilitas) as $fac)
                    <span style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:4px;
                                 padding:2px 8px;font-size:0.75rem;color:#166534;font-weight:500;">
                        {{ trim($fac) }}
                    </span>
                    @endforeach
                </div>
                @endif

                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <a href="{{ route('owner.schedules') }}" class="btn-brand btn-sm">
                        <i data-lucide="calendar-days" style="width:13px;height:13px;"></i>
                        Kelola Jadwal
                    </a>
                    {{-- Tombol Edit — dihubungkan ke route owner.fields.edit --}}
                    <a href="{{ route('owner.fields.edit', $lapangan) }}" class="btn-outline btn-sm">
                        <i data-lucide="edit-2" style="width:13px;height:13px;"></i>
                        Edit Lapangan
                    </a>
                    {{-- Tombol Hapus --}}
                    <form method="POST" action="{{ route('owner.fields.destroy', $lapangan) }}"
                          onsubmit="return confirm('Hapus lapangan ini?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-outline btn-sm"
                                style="color:#DC2626;border-color:#FECACA;">
                            <i data-lucide="trash-2" style="width:13px;height:13px;"></i>
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ─── Lapangan pending approval (FR-B2, FR-B3) ──────────── --}}
@if($pendingFields->count())
<h3 class="section-title" style="font-size:0.9375rem;margin-bottom:14px;">Menunggu Persetujuan Admin</h3>
<div style="display:flex;flex-direction:column;gap:12px;">
    @foreach($pendingFields as $lapangan)
    <div class="card" style="padding:16px 18px;border-left:3px solid #F59E0B;">
        <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
            <div>
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin-bottom:4px;">
                    {{ $lapangan->nama }}
                    <span class="badge badge-warning" style="margin-left:8px;">
                        <i data-lucide="clock" style="width:10px;height:10px;"></i>
                        Menunggu Review Admin
                    </span>
                </div>
                <div style="font-size:0.8125rem;color:#64748B;">
                    {{ $lapangan->cabangOlahraga->nama_cabor }} • {{ $lapangan->lokasi }}
                </div>
                <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">
                    Diajukan: {{ $lapangan->created_at->format('d M Y') }}
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <p style="font-size:0.8125rem;color:#92400E;margin:0 0 8px;">
                    Lapangan belum tayang ke publik<br>hingga Admin menyetujui.
                </p>
                {{-- Tombol Edit untuk lapangan pending --}}
                <a href="{{ route('owner.fields.edit', $lapangan) }}" class="btn-outline btn-sm">
                    <i data-lucide="edit-2" style="width:13px;height:13px;"></i>
                    Edit Data
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- Kosong --}}
@if($lapangans->isEmpty())
<div style="text-align:center;padding:56px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;margin-top:8px;">
    <i data-lucide="map-pin-off" style="width:40px;height:40px;color:#94A3B8;margin-bottom:12px;opacity:0.5;"></i>
    <p style="font-size:0.9375rem;color:#64748B;margin:0 0 12px;">Kamu belum mendaftarkan lapangan apapun.</p>
    <a href="{{ route('owner.fields.create') }}" class="btn-brand">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Daftarkan Lapangan Pertamamu
    </a>
</div>
@endif

@endsection
