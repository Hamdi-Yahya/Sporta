@extends('layouts.dashboard')

@section('title', 'Beranda Player')
@section('page_title', 'Beranda')
@section('sidebar_role', 'Player')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
$activeBookings = isset($recentBookings) ? $recentBookings : collect();
@endphp

{{-- ─── Welcome banner ─────────────────────────────────────── --}}
<div style="background:#0F2E1C;border-radius:12px;padding:28px 32px;
            display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <p style="font-size:0.8125rem;color:#86EFAC;margin:0 0 4px;">Selamat datang kembali,</p>
        <h2 style="font-family:'Poppins',sans-serif;font-size:1.375rem;font-weight:700;
                   color:#fff;margin:0;">{{ Auth::user()->name }}</h2>
        <p style="font-size:0.875rem;color:#A7F3D0;margin:8px 0 0;">Member sejak {{ Auth::user()->created_at->translatedFormat('F Y') }}</p>
    </div>
    <a href="/player/booking" class="btn-brand" style="white-space:nowrap;">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Booking Lapangan
    </a>
</div>

{{-- ─── Stats Row ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
    @foreach([
        ['label'=>'Total Booking',   'value'=> $stats['total_booking'] ?? 0,   'icon'=>'calendar-check',  'color'=>'#16A34A'],
        ['label'=>'Sedang Aktif',    'value'=> $stats['aktif'] ?? 0,    'icon'=>'clock',           'color'=>'#F59E0B'],
        ['label'=>'Menunggu',        'value'=> $stats['menunggu'] ?? 0, 'icon'=>'users',           'color'=>'#EA580C'],
        ['label'=>'Selesai',         'value'=> $stats['selesai'] ?? 0,  'icon'=>'star',            'color'=>'#7C3AED'],
    ] as $stat)
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:0.8125rem;color:#64748B;font-weight:500;">{{ $stat['label'] }}</span>
            <div style="width:34px;height:34px;background:{{ $stat['color'] }}15;border-radius:8px;
                        display:flex;align-items:center;justify-content:center;">
                <i data-lucide="{{ $stat['icon'] }}" style="width:17px;height:17px;color:{{ $stat['color'] }};"></i>
            </div>
        </div>
        <div style="font-family:'Poppins',sans-serif;font-size:1.75rem;font-weight:800;color:#1E293B;">
            {{ $stat['value'] }}
        </div>
    </div>
    @endforeach
</div>

{{-- ─── Active Bookings + Quick Access ─────────────────────── --}}
<div style="display:grid;grid-template-columns:1.4fr 1fr;gap:20px;">

    {{-- Active Bookings --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h3 class="section-title" style="font-size:1rem;">Booking Aktif</h3>
            <a href="/player/history" style="font-size:0.8125rem;color:#16A34A;font-weight:600;text-decoration:none;">
                Lihat Semua →
            </a>
        </div>

        <div style="display:flex;flex-direction:column;gap:12px;">
            @foreach($activeBookings as $b)
            <div class="card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;">
                            {{ $b['field'] }}
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;margin-top:2px;">{{ $b['sport'] }}</div>
                    </div>
                    <x-badge-status :status="$b['status']" />
                </div>
                <div style="display:flex;gap:16px;font-size:0.8125rem;color:#64748B;">
                    <span style="display:flex;align-items:center;gap:5px;">
                        <i data-lucide="calendar" style="width:13px;height:13px;"></i>{{ $b['date'] }}
                    </span>
                    <span style="display:flex;align-items:center;gap:5px;">
                        <i data-lucide="clock" style="width:13px;height:13px;"></i>{{ $b['time'] }}
                    </span>
                </div>
                @if($b['status'] === 'menunggu_pembayaran')
                <div style="margin-top:12px;padding-top:12px;border-top:1px solid #F1F5F9;
                            display:flex;align-items:center;justify-content:space-between;">
                    <div style="display:flex;align-items:center;gap:6px;font-size:0.8125rem;color:#DC2626;">
                        <i data-lucide="timer" style="width:13px;height:13px;"></i>
                        Batas bayar: <strong>23:47</strong>
                    </div>
                    <a href="/player/booking/payment" class="btn-brand btn-sm">Upload Bukti Transfer</a>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Quick Access --}}
    <div>
        <h3 class="section-title" style="font-size:1rem;margin-bottom:14px;">Akses Cepat</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            @foreach([
                ['title'=>'Cari Lapangan',     'icon'=>'map-pin',        'href'=>'/player/booking',      'bg'=>'#16A34A'],
                ['title'=>'Open Match',         'icon'=>'users',          'href'=>'/player/open-match',   'bg'=>'#EA580C'],
                ['title'=>'Komunitas',          'icon'=>'message-circle', 'href'=>'/player/community',    'bg'=>'#2563EB'],
                ['title'=>'Riwayat Booking',    'icon'=>'clock',          'href'=>'/player/history',      'bg'=>'#7C3AED'],
            ] as $qa)
            <a href="{{ $qa['href'] }}" style="text-decoration:none;">
                <div class="card" style="padding:18px;text-align:center;cursor:pointer;
                            transition:box-shadow 0.2s,transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)'"
                     onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <div style="width:44px;height:44px;background:{{ $qa['bg'] }};border-radius:10px;
                                display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i data-lucide="{{ $qa['icon'] }}" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div style="font-family:'Poppins',sans-serif;font-size:0.8125rem;font-weight:700;color:#1E293B;">
                        {{ $qa['title'] }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Open Match Tersedia --}}
        <div style="margin-top:16px;">
            <h3 class="section-title" style="font-size:1rem;margin-bottom:12px;">Open Match Tersedia</h3>
            <div class="card" style="padding:16px;border-left:3px solid #EA580C;">
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;color:#1E293B;margin-bottom:4px;">
                    Bulu Tangkis — GOR Arinda
                </div>
                <div style="font-size:0.8125rem;color:#64748B;margin-bottom:10px;">
                    Minggu 29 Agt • 10:00–11:00 • Rp 22.000/orang
                </div>
                <div class="progress-bar" style="margin-bottom:6px;">
                    <div class="progress-bar-fill" style="width:60%;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;">
                    <span style="color:#64748B;">3 dari 5 slot terisi</span>
                    <a href="/player/open-match" style="color:#EA580C;font-weight:600;text-decoration:none;">Bergabung →</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
