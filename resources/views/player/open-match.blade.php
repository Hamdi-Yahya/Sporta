@extends('layouts.dashboard')

@section('title', 'Cari Partner — Open Match')
@section('page_title', 'Cari Partner / Open Match')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
/* Dummy data slot Open Match (FR-E1) */
$openMatches = [
    ['id'=>1, 'field'=>'Futsal Planet Pekalongan',  'sport'=>'Futsal',      'location'=>'Pekalongan Barat',
     'date'=>'Sabtu, 28 Agt', 'time'=>'16:00–17:00', 'quota_filled'=>5,  'quota_total'=>10,
     'price_per_seat'=>'9.000', 'total_price'=>'90.000', 'status'=>'open'],

    ['id'=>2, 'field'=>'GOR Bulu Tangkis Arinda',   'sport'=>'Bulu Tangkis','location'=>'Pekalongan Timur',
     'date'=>'Minggu, 29 Agt','time'=>'08:00–09:00', 'quota_filled'=>3,  'quota_total'=>5,
     'price_per_seat'=>'22.000','total_price'=>'110.000','status'=>'open'],

    ['id'=>3, 'field'=>'Lapangan Basket Pemuda',    'sport'=>'Basket',      'location'=>'Kota Pekalongan',
     'date'=>'Minggu, 29 Agt','time'=>'15:00–16:00', 'quota_filled'=>8,  'quota_total'=>10,
     'price_per_seat'=>'7.500', 'total_price'=>'75.000', 'status'=>'open'],

    ['id'=>4, 'field'=>'Mini Soccer Arena',         'sport'=>'Mini Soccer', 'location'=>'Pekalongan Barat',
     'date'=>'Senin, 30 Agt',  'time'=>'17:00–18:00', 'quota_filled'=>12, 'quota_total'=>14,
     'price_per_seat'=>'12.143','total_price'=>'170.000','status'=>'open'],

    ['id'=>5, 'field'=>'Padel Court Pekalongan',    'sport'=>'Padel',       'location'=>'Pekalongan Utara',
     'date'=>'Selasa, 31 Agt', 'time'=>'10:00–11:00', 'quota_filled'=>4,  'quota_total'=>4,
     'price_per_seat'=>'32.500','total_price'=>'130.000','status'=>'full'],

    ['id'=>6, 'field'=>'Tenis Indoor Batik City',   'sport'=>'Tenis',       'location'=>'Pekalongan Selatan',
     'date'=>'Rabu, 1 Sep',    'time'=>'07:00–08:00', 'quota_filled'=>1,  'quota_total'=>4,
     'price_per_seat'=>'27.500','total_price'=>'110.000','status'=>'open'],
];

$sports = ['Futsal','Bulu Tangkis','Basket','Tenis','Padel','Mini Soccer'];
@endphp

{{-- ─── Filter bar ──────────────────────────────────────────── --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <i data-lucide="filter" style="width:16px;height:16px;color:#64748B;"></i>
    <span style="font-size:0.8125rem;font-weight:600;color:#1E293B;">Filter:</span>
    <select class="form-input" style="width:auto;padding:7px 12px;font-size:0.8125rem;">
        <option value="">Semua Olahraga</option>
        @foreach($sports as $s)
            <option>{{ $s }}</option>
        @endforeach
    </select>
    <select class="form-input" style="width:auto;padding:7px 12px;font-size:0.8125rem;">
        <option>Tanggal Terdekat</option>
        <option>Slot Hampir Penuh</option>
        <option>Harga Terendah</option>
    </select>
    <label style="display:flex;align-items:center;gap:6px;font-size:0.8125rem;cursor:pointer;">
        <input type="checkbox" style="accent-color:#16A34A;"> Sembunyikan yang Penuh
    </label>
    <div style="margin-left:auto;font-size:0.8125rem;color:#64748B;">
        <strong style="color:#1E293B;">{{ count(array_filter($openMatches, fn($m) => $m['status']==='open')) }}</strong> slot tersedia
    </div>
</div>

{{-- ─── Open Match Cards ────────────────────────────────────── --}}
<div style="display:flex;flex-direction:column;gap:14px;">
    @foreach($openMatches as $match)
    @php
        $pct   = round($match['quota_filled'] / $match['quota_total'] * 100);
        $isFull = $match['status'] === 'full';
        $remaining = $match['quota_total'] - $match['quota_filled'];
    @endphp
    <div class="card" style="padding:20px;{{ $isFull ? 'opacity:0.65;' : '' }}">
        <div style="display:grid;grid-template-columns:1fr auto;gap:20px;align-items:center;">

            {{-- Left: info --}}
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                    {{-- Sport label --}}
                    <span style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:4px;
                                 font-size:0.75rem;font-weight:700;color:#EA580C;padding:2px 9px;">
                        {{ $match['sport'] }}
                    </span>

                    @if($isFull)
                        <span class="badge badge-danger">Penuh</span>
                    @else
                        <span class="badge badge-success">Tersedia</span>
                    @endif
                </div>

                <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;
                           color:#1E293B;margin:0 0 6px;">{{ $match['field'] }}</h3>

                <div style="display:flex;gap:16px;font-size:0.8125rem;color:#64748B;flex-wrap:wrap;margin-bottom:14px;">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <i data-lucide="map-pin" style="width:13px;height:13px;"></i>{{ $match['location'] }}
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;">
                        <i data-lucide="calendar" style="width:13px;height:13px;"></i>{{ $match['date'] }}
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;">
                        <i data-lucide="clock" style="width:13px;height:13px;"></i>{{ $match['time'] }}
                    </span>
                </div>

                {{-- Progress kuota (§3.7) --}}
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="progress-bar" style="flex:1;">
                        <div class="progress-bar-fill" style="width:{{ $pct }}%;
                             background-color:{{ $pct >= 100 ? '#DC2626' : ($pct >= 75 ? '#F59E0B' : '#EA580C') }};"></div>
                    </div>
                    <span style="font-size:0.8125rem;font-weight:700;color:#1E293B;white-space:nowrap;">
                        {{ $match['quota_filled'] }}/{{ $match['quota_total'] }} Pemain
                    </span>
                </div>

                @if(!$isFull)
                <p style="font-size:0.75rem;color:#EA580C;font-weight:600;margin:5px 0 0;">
                    Masih ada {{ $remaining }} kursi tersisa
                </p>
                @endif
            </div>

            {{-- Right: price & CTA --}}
            <div style="text-align:right;min-width:160px;">
                <div style="font-size:0.75rem;color:#94A3B8;margin-bottom:2px;">Harga per orang</div>
                <div style="font-family:'Poppins',sans-serif;font-size:1.375rem;font-weight:800;color:#166534;margin-bottom:4px;">
                    Rp {{ $match['price_per_seat'] }}
                </div>
                <div style="font-size:0.75rem;color:#94A3B8;margin-bottom:14px;">
                    dari total Rp {{ $match['total_price'] }}
                </div>

                @if(!$isFull)
                <a href="/player/booking/payment" class="btn-brand">
                    <i data-lucide="user-plus" style="width:15px;height:15px;"></i>
                    Bergabung
                </a>
                @else
                <button class="btn-brand" disabled
                        style="opacity:0.4;cursor:not-allowed;background:#64748B;">
                    Slot Penuh
                </button>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Empty state hint --}}
<div style="margin-top:16px;text-align:center;padding:20px;">
    <p style="font-size:0.875rem;color:#94A3B8;">
        Tidak ada slot Open Match yang sesuai?
        <a href="/player/booking" style="color:#16A34A;font-weight:600;text-decoration:none;">Cari & buat booking biasa →</a>
    </p>
</div>

@endsection
