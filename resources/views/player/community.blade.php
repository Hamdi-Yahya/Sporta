@extends('layouts.dashboard')

@section('title', 'Komunitas Olahraga')
@section('page_title', 'Komunitas')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
$totalUsers = \App\Models\User::count();
@endphp

<style>
/* ── Responsive Community Index Layout ── */
@media (max-width: 768px) {
    .community-layout {
        grid-template-columns: 1fr !important;
        height: auto !important;
        min-height: auto !important;
    }
    .community-sidebar {
        border-right: none !important;
        border-bottom: 1px solid #E2E8F0 !important;
        /* tampilkan semua cabor, tidak dibatasi tinggi di index */
        max-height: none !important;
        height: auto !important;
    }
    .community-chat-empty {
        min-height: 200px !important;
        padding: 32px 16px !important;
    }
}
</style>

{{-- ─── Layout: daftar komunitas (kiri) + chat (kanan) ──────── --}}
<div class="community-layout" style="display:grid;grid-template-columns:300px 1fr;gap:0;height:calc(100vh - 140px);
            border:1px solid #E2E8F0;border-radius:10px;overflow:hidden;background:#fff;">

    {{-- ─── Sidebar Daftar Komunitas ────────────────────────── --}}
    <div class="community-sidebar" style="border-right:1px solid #E2E8F0;display:flex;flex-direction:column;">

        <div style="padding:14px 16px;border-bottom:1px solid #F1F5F9;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 10px;">Ruang Komunitas</h3>
            <form method="GET" action="{{ route(Auth::user()->isOwner() ? 'owner.community' : 'player.community') }}">
                <input type="text" name="search" class="form-input" style="font-size:0.8125rem;"
                       placeholder="Cari komunitas..." value="{{ request('search') }}">
            </form>
        </div>

        <div style="overflow-y:auto;flex:1;">
            @foreach($komunitasList as $komunitas)
            @php 
                $cabor = $komunitas->cabangOlahraga; 
                $bg = '#16A34A'; $icon = 'message-circle';
                if(str_contains(strtolower($cabor->nama_cabor), 'futsal')) { $bg = '#16A34A'; $icon = 'zap'; }
                elseif(str_contains(strtolower($cabor->nama_cabor), 'bulu tangkis')) { $bg = '#0891B2'; $icon = 'wind'; }
                elseif(str_contains(strtolower($cabor->nama_cabor), 'basket')) { $bg = '#EA580C'; $icon = 'circle-dot'; }
                elseif(str_contains(strtolower($cabor->nama_cabor), 'tenis')) { $bg = '#7C3AED'; $icon = 'target'; }
            @endphp
            <a href="{{ Auth::user()->isOwner() ? route('owner.community.show', $komunitas) : route('player.community.show', $komunitas) }}" 
                 style="display:block;text-decoration:none;padding:12px 14px;cursor:pointer;transition:background 0.15s;border-right:3px solid transparent;"
                 onmouseover="this.style.background='#F8FAFC'"
                 onmouseout="this.style.background='transparent'">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:40px;height:40px;background:{{ $bg }};border-radius:10px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $icon }}" style="width:18px;height:18px;color:#fff;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:6px;">
                            <span style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;
                                         color:#1E293B;">
                                {{ $cabor->nama_cabor }}
                            </span>
                        </div>
                        <div style="font-size:0.75rem;color:#64748B;white-space:nowrap;overflow:hidden;
                                    text-overflow:ellipsis;margin-top:2px;">
                            {{ $komunitas->deskripsi }}
                        </div>
                        <div style="font-size:0.7rem;color:#94A3B8;margin-top:2px;">
                            {{ number_format($totalUsers) }} anggota
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- ─── Area Chat Empty State ───────────────────────────── --}}
    <div class="community-chat-empty" style="display:flex;flex-direction:column;align-items:center;justify-content:center;background:#F8FAFC;">
        <i data-lucide="message-square" style="width:48px;height:48px;color:#CBD5E1;margin-bottom:16px;"></i>
        <div style="font-family:'Poppins',sans-serif;font-size:1.125rem;font-weight:700;color:#475569;margin-bottom:8px;text-align:center;">
            Ruang Obrolan Komunitas
        </div>
        <div style="font-size:0.875rem;color:#94A3B8;max-width:300px;text-align:center;">
            Pilih salah satu komunitas cabang olahraga di daftar sebelah kiri untuk mulai membaca dan mengirim pesan.
        </div>
    </div>
</div>

@endsection
