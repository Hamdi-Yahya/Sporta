@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('sidebar_role', 'Administrator')
@section('page_title', 'Dashboard Admin')

@section('sidebar_nav')
@php $current = request()->route()->getName() ?? ''; @endphp
<ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
    <li>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ str_starts_with($current, 'admin.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Beranda</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.approval') }}"
           class="sidebar-link {{ str_starts_with($current, 'admin.approval') ? 'active' : '' }}"
           style="position:relative;">
            <i data-lucide="shield-check" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Approval Lapangan</span>
            @if(($pendingCount ?? 0) > 0)
            <span style="margin-left:auto;background:#EA580C;color:#fff;font-size:0.6875rem;font-weight:700;padding:1px 7px;border-radius:10px;">{{ $pendingCount ?? 0 }}</span>
            @endif
        </a>
    </li>
</ul>
@endsection

@section('content')

{{-- Stats Cards --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px;">
    @php
    $cards = [
        ['label'=>'Total User',        'value'=>$stats['total_user'] ?? 0,       'icon'=>'users',        'color'=>'#16A34A'],
        ['label'=>'Total Owner',       'value'=>$stats['total_owner'] ?? 0,      'icon'=>'building-2',   'color'=>'#2563EB'],
        ['label'=>'Total Lapangan',    'value'=>$stats['total_lapangan'] ?? 0,   'icon'=>'map-pin',      'color'=>'#7C3AED'],
        ['label'=>'Pending Approval',  'value'=>$stats['pending_approval'] ?? 0, 'icon'=>'clock',        'color'=>'#F59E0B'],
        ['label'=>'Total Booking',     'value'=>$stats['total_booking'] ?? 0,    'icon'=>'calendar-check','color'=>'#EA580C'],
    ];
    @endphp
    @foreach($cards as $c)
    <div style="background:#fff;border:1px solid #E2E8F0;border-radius:8px;padding:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
            <span style="font-size:0.8125rem;color:#64748B;">{{ $c['label'] }}</span>
            <i data-lucide="{{ $c['icon'] }}" style="width:18px;height:18px;color:{{ $c['color'] }};"></i>
        </div>
        <div style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:700;color:#1E293B;">{{ $c['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Pending Approvals Preview --}}
<div style="background:#fff;border:1px solid #E2E8F0;border-radius:8px;padding:20px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <h3 style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:#1E293B;margin:0;">Pengajuan Lapangan Terbaru</h3>
        <a href="{{ route('admin.approval') }}" style="font-size:0.8125rem;color:#16A34A;text-decoration:none;font-weight:600;">Lihat Semua</a>
    </div>

    @forelse($pendingLapangans as $lap)
    <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 0;border-bottom:1px solid #F1F5F9;">
        <div>
            <div style="font-weight:600;color:#1E293B;font-size:0.875rem;">{{ $lap->nama }}</div>
            <div style="font-size:0.75rem;color:#64748B;">{{ $lap->cabangOlahraga->nama_cabor }} — {{ $lap->owner->name }}</div>
        </div>
        <span style="background:#FEF3C7;color:#92400E;font-size:0.75rem;font-weight:600;padding:2px 10px;border-radius:4px;">Pending</span>
    </div>
    @empty
    <p style="font-size:0.875rem;color:#94A3B8;text-align:center;padding:20px 0;">Tidak ada pengajuan baru.</p>
    @endforelse
</div>
@endsection
