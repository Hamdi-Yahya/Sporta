@extends('layouts.dashboard')

@section('title', 'Dashboard Owner')
@section('page_title', 'Beranda Owner')
@section('sidebar_role', 'Owner')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

{{-- ─── Welcome banner ─────────────────────────────────────── --}}
<div style="background:#0F2E1C;border-radius:12px;padding:24px 28px;
            display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <p style="font-size:0.8125rem;color:#86EFAC;margin:0 0 4px;">Dashboard Pemilik Lapangan</p>
        <h2 style="font-family:'Poppins',sans-serif;font-size:1.25rem;font-weight:700;color:#fff;margin:0;">
            {{ Auth::user()->nama_usaha ?? Auth::user()->name }}
        </h2>
        <p style="font-size:0.875rem;color:#A7F3D0;margin:6px 0 0;">
            {{ Auth::user()->alamat ?? '' }}
            @if($stats['total_lapangan'] > 0)
            <span style="margin-left:8px;" class="badge badge-success">Aktif & Terverifikasi</span>
            @endif
        </p>
    </div>
    <div style="text-align:right;">
        <div style="font-size:0.75rem;color:#86EFAC;margin-bottom:4px;">Perlu diverifikasi hari ini</div>
        <div style="font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:#FBBF24;">{{ $stats['menunggu_verifikasi'] }}</div>
        <a href="/owner/verify-booking" class="btn-brand btn-sm" style="margin-top:6px;">Verifikasi Sekarang</a>
    </div>
</div>

{{-- ─── Stats ───────────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    @foreach([
        ['label'=>'Total Pendapatan Bulan Ini', 'value'=>'Rp ' . number_format($stats['pendapatan_bulan_ini'], 0, ',', '.'), 'icon'=>'wallet',       'color'=>'#16A34A'],
        ['label'=>'Booking Bulan Ini',           'value'=> $stats['booking_bulan_ini'],        'icon'=>'calendar-check','color'=>'#2563EB'],
        ['label'=>'Menunggu Verifikasi',         'value'=> $stats['menunggu_verifikasi'],         'icon'=>'shield-check',  'color'=>'#EA580C'],
        ['label'=>'Rating Rata-rata',            'value'=> number_format($stats['rating_rata'], 1) . ' ★',     'icon'=>'star',          'color'=>'#F59E0B'],
    ] as $s)
    <div class="stat-card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
            <span style="font-size:0.75rem;color:#64748B;font-weight:500;line-height:1.4;">{{ $s['label'] }}</span>
            <div style="width:34px;height:34px;background:{{ $s['color'] }}15;border-radius:8px;
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="{{ $s['icon'] }}" style="width:16px;height:16px;color:{{ $s['color'] }};"></i>
            </div>
        </div>
        <div style="font-family:'Poppins',sans-serif;font-size:1.625rem;font-weight:800;color:#1E293B;">
            {{ $s['value'] }}
        </div>
    </div>
    @endforeach
</div>

{{-- ─── Content grid ────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;">

    {{-- Booking Terbaru --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h3 class="section-title" style="font-size:1rem;">Booking Terbaru</h3>
            <a href="/owner/verify-booking" style="font-size:0.8125rem;color:#16A34A;font-weight:600;text-decoration:none;">
                Lihat Semua →
            </a>
        </div>

        <div class="card" style="overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:0.8125rem;">
                <thead>
                    <tr style="background:#F8FAFC;border-bottom:1px solid #E2E8F0;">
                        <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Pemesan</th>
                        <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Jadwal</th>
                        <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Total</th>
                        <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $bk)
                    <tr style="border-bottom:1px solid #F1F5F9;">
                        <td style="padding:10px 14px;font-weight:600;color:#1E293B;">
                            {{ $bk->sumber_booking === 'offline' ? $bk->nama_pemesan_offline : $bk->user->name }}
                            @if($bk->sumber_booking === 'offline')
                            <span style="font-size:0.55rem;background:#475569;color:#fff;padding:1px 4px;border-radius:3px;font-weight:600;margin-left:4px;">OFFLINE</span>
                            @endif
                        </td>
                        <td style="padding:10px 14px;color:#475569;">
                            {{ \Carbon\Carbon::parse($bk->slot->tanggal)->translatedFormat('d M Y') }}<br>
                            <span style="font-size:0.75rem;color:#94A3B8;">{{ \Carbon\Carbon::parse($bk->slot->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($bk->slot->jam_selesai)->format('H:i') }}</span>
                        </td>
                        <td style="padding:10px 14px;font-weight:700;color:#166534;">Rp {{ number_format($bk->total_harga, 0, ',', '.') }}</td>
                        <td style="padding:10px 14px;"><x-badge-status :status="$bk->status" /></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align:center;padding:20px;color:#64748B;">Belum ada booking terbaru.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pendapatan & Lapangan --}}
    <div>
        {{-- Slot hari ini --}}
        <h3 class="section-title" style="font-size:1rem;margin-bottom:14px;">Slot Hari Ini — {{ now()->translatedFormat('l, d M') }}</h3>
        <div class="card" style="padding:16px;margin-bottom:16px;">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">
                @forelse($todaySlots as $s)
                <div style="border-radius:6px;padding:8px;text-align:center;font-size:0.75rem;
                            {{ $s->status==='dibooking' ? 'background:#DCFCE7;color:#166534;border:1px solid #BBF7D0;' : 'background:#F8FAFC;color:#94A3B8;border:1px solid #E2E8F0;' }}
                            {{ $s->tipe==='open_match' ? 'background:#FFF7ED !important;color:#9A3412 !important;border-color:#FED7AA !important;' : '' }}">
                    <div style="font-weight:700;">{{ \Carbon\Carbon::parse($s->jam_mulai)->format('H:i') }}</div>
                    @if($s->tipe==='open_match')
                        <div style="font-size:0.6875rem;">OM {{ $s->kuota_terisi }}/{{ $s->kuota_total }}</div>
                    @elseif($s->status==='dibooking')
                        <div style="font-size:0.6875rem;">Terpesan</div>
                    @else
                        <div style="font-size:0.6875rem;">Kosong</div>
                    @endif
                </div>
                @empty
                <div style="grid-column: span 3; text-align:center;color:#64748B;font-size:0.8125rem;">
                    Belum ada slot untuk hari ini.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Quick links --}}
        <h3 class="section-title" style="font-size:1rem;margin-bottom:12px;">Kelola</h3>
        <div style="display:flex;flex-direction:column;gap:8px;">
            @foreach([
                ['href'=>'/owner/fields',         'icon'=>'map-pin',    'title'=>'Kelola Lapangan Saya',    'sub'=> $stats['total_lapangan'] . ' lapangan aktif'],
                ['href'=>'/owner/schedules',       'icon'=>'calendar-days','title'=>'Kelola Jadwal & Slot', 'sub'=>'Atur slot besok'],
                ['href'=>'/owner/verify-booking',  'icon'=>'shield-check','title'=>'Verifikasi Booking',    'sub'=> $stats['menunggu_verifikasi'] . ' menunggu'],
            ] as $link)
            <a href="{{ $link['href'] }}" style="text-decoration:none;">
                <div class="card" style="padding:12px 14px;display:flex;align-items:center;gap:12px;cursor:pointer;
                            transition:box-shadow 0.15s;"
                     onmouseover="this.style.boxShadow='0 2px 12px rgba(0,0,0,0.07)'"
                     onmouseout="this.style.boxShadow=''">
                    <i data-lucide="{{ $link['icon'] }}" style="width:18px;height:18px;color:#16A34A;flex-shrink:0;"></i>
                    <div>
                        <div style="font-weight:700;font-size:0.875rem;color:#1E293B;">{{ $link['title'] }}</div>
                        <div style="font-size:0.75rem;color:#64748B;">{{ $link['sub'] }}</div>
                    </div>
                    <i data-lucide="chevron-right" style="width:16px;height:16px;color:#CBD5E1;margin-left:auto;"></i>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

@endsection
