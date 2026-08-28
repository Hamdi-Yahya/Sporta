@extends('layouts.dashboard')

@section('title', 'Detail Lapangan — Futsal Planet')
@section('page_title', 'Detail Lapangan')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
/* Dummy data lapangan & slot */
$field = [
    'name'        => 'Futsal Planet Pekalongan',
    'sport'       => 'Futsal',
    'type'        => 'Indoor',
    'location'    => 'Jl. Dr. Cipto No. 12, Pekalongan Barat',
    'phone'       => '0812-3456-7890',
    'rating'      => 4.8,
    'reviewCount' => 41,
    'facilities'  => ['Parkir Luas', 'Kamar Ganti', 'Toilet Bersih', 'Kantin', 'AC', 'Wifi'],
    'desc'        => 'Lapangan futsal berstandar internasional dengan lantai vinyl premium. Cocok untuk pertandingan kompetitif maupun latihan rutin. Dilengkapi kursi penonton dan penerangan LED terang.',
];

$slots = [
    ['time'=>'07:00–08:00', 'type'=>'normal',     'price'=>'80.000',  'status'=>'available'],
    ['time'=>'08:00–09:00', 'type'=>'normal',     'price'=>'80.000',  'status'=>'booked'],
    ['time'=>'09:00–10:00', 'type'=>'normal',     'price'=>'80.000',  'status'=>'available'],
    ['time'=>'10:00–11:00', 'type'=>'open_match', 'price'=>'18.000',  'status'=>'available', 'quota'=>'5/10'],
    ['time'=>'11:00–12:00', 'type'=>'normal',     'price'=>'80.000',  'status'=>'available'],
    ['time'=>'12:00–13:00', 'type'=>'normal',     'price'=>'90.000',  'status'=>'booked'],
    ['time'=>'13:00–14:00', 'type'=>'open_match', 'price'=>'20.000',  'status'=>'available', 'quota'=>'8/10'],
    ['time'=>'14:00–15:00', 'type'=>'normal',     'price'=>'90.000',  'status'=>'available'],
    ['time'=>'15:00–16:00', 'type'=>'normal',     'price'=>'90.000',  'status'=>'available'],
    ['time'=>'16:00–17:00', 'type'=>'normal',     'price'=>'90.000',  'status'=>'booked'],
    ['time'=>'17:00–18:00', 'type'=>'open_match', 'price'=>'22.000',  'status'=>'available', 'quota'=>'2/10'],
    ['time'=>'18:00–19:00', 'type'=>'normal',     'price'=>'100.000', 'status'=>'available'],
    ['time'=>'19:00–20:00', 'type'=>'normal',     'price'=>'100.000', 'status'=>'available'],
    ['time'=>'20:00–21:00', 'type'=>'normal',     'price'=>'100.000', 'status'=>'booked'],
    ['time'=>'21:00–22:00', 'type'=>'normal',     'price'=>'90.000',  'status'=>'available'],
];

$reviews = [
    ['user'=>'Bima S.',    'rating'=>5, 'date'=>'25 Agt 2026', 'text'=>'Lapangan bersih banget, AC dingin, penjaga ramah. Pasti balik lagi!'],
    ['user'=>'Dinda R.',   'rating'=>4, 'date'=>'22 Agt 2026', 'text'=>'Bagus, parkir luas. Agak susah sinyal tapi masih oke.'],
    ['user'=>'Rizky M.',   'rating'=>5, 'date'=>'20 Agt 2026', 'text'=>'Top! Booking via SPORTA gampang banget, konfirmasi cepat.'],
];
@endphp

<div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:flex-start;">

    {{-- ─── Konten Utama (kiri) ───────────────────────────────── --}}
    <div>
        {{-- Foto placeholder --}}
        <div style="height:280px;background:linear-gradient(135deg,#1E293B,#334155);
                    border-radius:12px;display:flex;align-items:center;justify-content:center;
                    margin-bottom:20px;position:relative;overflow:hidden;">
            <div style="text-align:center;color:#94A3B8;">
                <i data-lucide="image" style="width:48px;height:48px;margin-bottom:8px;"></i>
                <p style="font-size:0.875rem;margin:0;">Foto Lapangan</p>
            </div>
            <div style="position:absolute;top:14px;left:14px;background:rgba(15,46,28,0.85);
                        color:#4ADE80;font-size:0.75rem;font-weight:700;padding:4px 11px;border-radius:4px;">
                {{ $field['sport'] }} • {{ $field['type'] }}
            </div>
        </div>

        {{-- Info header --}}
        <div style="margin-bottom:20px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;">
                <div>
                    <h1 style="font-family:'Poppins',sans-serif;font-size:1.375rem;font-weight:800;
                               color:#1E293B;margin:0 0 6px;">{{ $field['name'] }}</h1>
                    <div style="display:flex;align-items:center;gap:8px;color:#64748B;font-size:0.875rem;">
                        <i data-lucide="map-pin" style="width:14px;height:14px;flex-shrink:0;"></i>
                        {{ $field['location'] }}
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="display:flex;align-items:center;gap:5px;justify-content:flex-end;margin-bottom:4px;">
                        @for($i=1;$i<=5;$i++)
                            <i data-lucide="star" style="width:15px;height:15px;color:{{ $i <= floor($field['rating']) ? '#F59E0B' : '#CBD5E1' }};fill:{{ $i <= floor($field['rating']) ? '#F59E0B' : 'transparent' }};"></i>
                        @endfor
                        <span style="font-weight:700;color:#1E293B;font-size:0.9375rem;">{{ $field['rating'] }}</span>
                    </div>
                    <span style="font-size:0.8125rem;color:#64748B;">{{ $field['reviewCount'] }} ulasan</span>
                </div>
            </div>
        </div>

        {{-- Fasilitas badges --}}
        <div style="margin-bottom:20px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 10px;">Fasilitas</h3>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                @foreach($field['facilities'] as $fac)
                <span style="display:inline-flex;align-items:center;gap:5px;
                             background:#F0FDF4;border:1px solid #BBF7D0;
                             border-radius:5px;padding:4px 10px;
                             font-size:0.8125rem;font-weight:500;color:#166534;">
                    <i data-lucide="check" style="width:12px;height:12px;"></i>
                    {{ $fac }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- Deskripsi --}}
        <div style="margin-bottom:24px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 8px;">Tentang Lapangan</h3>
            <p style="font-size:0.875rem;color:#475569;line-height:1.7;margin:0;">{{ $field['desc'] }}</p>
        </div>

        {{-- ─── Grid Pilih Slot (§3.6 §3.7 §3.8) ─────────────── --}}
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin:0;">
                    Pilih Tanggal & Jam
                </h3>
                {{-- Date picker --}}
                <input type="date" class="form-input" style="width:auto;padding:8px 12px;font-size:0.8125rem;"
                       value="2026-08-28">
            </div>

            {{-- Legend --}}
            <div style="display:flex;gap:16px;margin-bottom:12px;font-size:0.75rem;flex-wrap:wrap;">
                <span style="display:flex;align-items:center;gap:5px;">
                    <span style="width:12px;height:12px;border:1.5px solid #CBD5E1;border-radius:3px;"></span>
                    Tersedia
                </span>
                <span style="display:flex;align-items:center;gap:5px;color:#EA580C;font-weight:600;">
                    <span style="width:12px;height:12px;border:1.5px solid #EA580C;border-radius:3px;background:#FFF7ED;"></span>
                    Open Match
                </span>
                <span style="display:flex;align-items:center;gap:5px;color:#94A3B8;">
                    <span style="width:12px;height:12px;border:1.5px solid #E2E8F0;border-radius:3px;background:#F8FAFC;"></span>
                    Sudah Dipesan
                </span>
            </div>

            {{-- Slot grid --}}
            <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;">
                @foreach($slots as $idx => $slot)
                    @if($slot['status'] === 'booked')
                        <div class="slot-booked">
                            <div style="font-size:0.75rem;font-weight:600;">{{ $slot['time'] }}</div>
                            <div style="font-size:0.7rem;margin-top:2px;">Terpesan</div>
                        </div>
                    @elseif($slot['type'] === 'open_match')
                        <div class="slot-open-match" onclick="selectSlot({{ $idx }}, '{{ $slot['time'] }}', '{{ $slot['price'] }}', 'Open Match')">
                            <div style="font-size:0.75rem;font-weight:700;">{{ $slot['time'] }}</div>
                            <div style="font-size:0.65rem;margin-top:2px;font-weight:600;">OPEN MATCH</div>
                            <div style="font-size:0.7rem;margin-top:1px;">{{ $slot['quota'] }} · Rp {{ $slot['price'] }}</div>
                        </div>
                    @else
                        <div class="slot-normal" id="slot-{{ $idx }}"
                             onclick="selectSlot({{ $idx }}, '{{ $slot['time'] }}', '{{ $slot['price'] }}', 'Biasa')">
                            <div style="font-size:0.75rem;font-weight:600;">{{ $slot['time'] }}</div>
                            <div style="font-size:0.7rem;color:#64748B;margin-top:2px;">Rp {{ $slot['price'] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Ulasan --}}
        <div style="margin-top:28px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 14px;">Ulasan Pemain</h3>
            <div style="display:flex;flex-direction:column;gap:12px;">
                @foreach($reviews as $rev)
                <div class="card" style="padding:14px 16px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <div style="width:32px;height:32px;border-radius:50%;background:#16A34A;
                                        display:flex;align-items:center;justify-content:center;">
                                <span style="font-size:0.75rem;font-weight:700;color:#fff;">
                                    {{ substr($rev['user'],0,1) }}
                                </span>
                            </div>
                            <span style="font-weight:600;font-size:0.875rem;color:#1E293B;">{{ $rev['user'] }}</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:4px;">
                            @for($i=1;$i<=5;$i++)
                                <i data-lucide="star" style="width:12px;height:12px;color:{{ $i<=$rev['rating']?'#F59E0B':'#CBD5E1' }};fill:{{ $i<=$rev['rating']?'#F59E0B':'transparent' }};"></i>
                            @endfor
                            <span style="font-size:0.75rem;color:#94A3B8;margin-left:4px;">{{ $rev['date'] }}</span>
                        </div>
                    </div>
                    <p style="font-size:0.875rem;color:#475569;margin:0;line-height:1.6;">{{ $rev['text'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ─── Panel Booking Kanan (sticky) ─────────────────────── --}}
    <div style="position:sticky;top:80px;">
        <div class="card" style="padding:22px;" id="booking-panel">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;
                       color:#1E293B;margin:0 0 16px;">Ringkasan Booking</h3>

            <div style="background:#F8FAFC;border-radius:8px;padding:14px;margin-bottom:16px;" id="slot-summary">
                <p style="font-size:0.8125rem;color:#94A3B8;text-align:center;margin:0;">
                    Pilih slot waktu di kalender untuk melanjutkan
                </p>
            </div>

            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                    <span style="color:#64748B;">Lapangan</span>
                    <span style="font-weight:600;color:#1E293B;">{{ $field['name'] }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;">
                    <span style="color:#64748B;">Tanggal</span>
                    <span style="font-weight:600;color:#1E293B;">Sabtu, 28 Agt 2026</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;" id="time-row">
                    <span style="color:#64748B;">Waktu</span>
                    <span style="font-weight:600;color:#94A3B8;" id="time-val">Belum dipilih</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.875rem;" id="type-row">
                    <span style="color:#64748B;">Jenis Slot</span>
                    <span style="font-weight:600;color:#94A3B8;" id="type-val">–</span>
                </div>
            </div>

            <div style="border-top:1px solid #F1F5F9;padding-top:14px;margin-bottom:18px;">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;color:#1E293B;">Total Bayar</span>
                    <span style="font-family:'Poppins',sans-serif;font-size:1.25rem;font-weight:800;color:#166534;" id="total-price">
                        –
                    </span>
                </div>
            </div>

            <a href="/player/booking/payment" id="btn-lanjut"
               class="btn-brand" style="width:100%;justify-content:center;
                       opacity:0.4;pointer-events:none;">
                <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                Lanjutkan
            </a>

            <p style="font-size:0.75rem;color:#94A3B8;text-align:center;margin:10px 0 0;line-height:1.5;">
                Slot akan dikunci 15 menit setelah booking dikonfirmasi
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedSlot = null;

    /* Pilih slot dari grid */
    function selectSlot(idx, time, price, type) {
        // Reset semua slot normal
        document.querySelectorAll('.slot-normal').forEach(el => el.classList.remove('selected'));

        // Tandai yang dipilih
        const el = document.getElementById('slot-' + idx);
        if (el) el.classList.add('selected');

        // Update panel kanan
        document.getElementById('time-val').textContent  = time;
        document.getElementById('time-val').style.color  = '#1E293B';
        document.getElementById('type-val').textContent  = type === 'Open Match' ? 'Open Match (per kursi)' : 'Booking Biasa';
        document.getElementById('type-val').style.color  = type === 'Open Match' ? '#EA580C' : '#1E293B';
        document.getElementById('total-price').textContent = 'Rp ' + price;

        document.getElementById('slot-summary').innerHTML =
            '<div style="text-align:center;">' +
            '<div style="font-size:0.8125rem;font-weight:700;color:#166534;">' + time + '</div>' +
            '<div style="font-size:0.75rem;color:#64748B;margin-top:2px;">' + type + '</div>' +
            '</div>';

        // Aktifkan tombol lanjut
        const btn = document.getElementById('btn-lanjut');
        btn.style.opacity = '1';
        btn.style.pointerEvents = 'auto';
    }
</script>
@endpush

@endsection
