@extends('layouts.dashboard')

@section('title', 'Riwayat Booking')
@section('page_title', 'Riwayat Booking & Rating')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
/* Dummy riwayat booking */
$bookings = [
    ['id'=>'#SPTA-001','field'=>'Futsal Planet Pekalongan',  'sport'=>'Futsal',      'date'=>'28 Agt 2026','time'=>'16:00–17:00',
     'price'=>'90.000', 'status'=>'terkonfirmasi', 'rated'=>false],
    ['id'=>'#SPTA-002','field'=>'GOR Bulu Tangkis Arinda',   'sport'=>'Bulu Tangkis','date'=>'22 Agt 2026','time'=>'08:00–09:00',
     'price'=>'55.000', 'status'=>'selesai',        'rated'=>true,  'rating'=>5, 'review'=>'Lapangan keren, booking gampang!'],
    ['id'=>'#SPTA-003','field'=>'Lapangan Basket Pemuda',    'sport'=>'Basket',      'date'=>'18 Agt 2026','time'=>'15:00–16:00',
     'price'=>'75.000', 'status'=>'ditolak',        'rated'=>false],
    ['id'=>'#SPTA-004','field'=>'Tenis Indoor Batik City',   'sport'=>'Tenis',       'date'=>'15 Agt 2026','time'=>'07:00–08:00',
     'price'=>'110.000','status'=>'selesai',        'rated'=>false],
    ['id'=>'#SPTA-005','field'=>'Padel Court Pekalongan',    'sport'=>'Padel',       'date'=>'10 Agt 2026','time'=>'10:00–11:00',
     'price'=>'130.000','status'=>'selesai',        'rated'=>true, 'rating'=>4, 'review'=>'Bagus, court terawat.'],
    ['id'=>'#SPTA-006','field'=>'Mini Soccer Arena',         'sport'=>'Mini Soccer', 'date'=>'5 Agt 2026', 'time'=>'17:00–18:00',
     'price'=>'85.000', 'status'=>'expired',        'rated'=>false],
];
@endphp

{{-- ─── Filter tabs ──────────────────────────────────────────── --}}
<div style="display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach(['Semua','Terkonfirmasi','Menunggu Pembayaran','Selesai','Ditolak/Expired'] as $tab)
    <button style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;
                   border:1.5px solid {{ $loop->first ? '#16A34A' : '#E2E8F0' }};
                   background:{{ $loop->first ? '#16A34A' : '#fff' }};
                   color:{{ $loop->first ? '#fff' : '#64748B' }};">
        {{ $tab }}
    </button>
    @endforeach
</div>

{{-- ─── Riwayat List ─────────────────────────────────────────── --}}
<div style="display:flex;flex-direction:column;gap:14px;">
    @foreach($bookings as $b)
    <div class="card" style="padding:18px 20px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;gap:12px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                               color:#1E293B;margin:0;">{{ $b['field'] }}</h3>
                    <x-badge-status :status="$b['status']" />
                </div>
                <div style="font-size:0.8125rem;color:#64748B;">{{ $b['sport'] }}</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#166534;">
                    Rp {{ $b['price'] }}
                </div>
                <div style="font-size:0.75rem;color:#94A3B8;">{{ $b['id'] }}</div>
            </div>
        </div>

        <div style="display:flex;gap:16px;font-size:0.8125rem;color:#64748B;margin-bottom:12px;flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:4px;">
                <i data-lucide="calendar" style="width:13px;height:13px;"></i>{{ $b['date'] }}
            </span>
            <span style="display:flex;align-items:center;gap:4px;">
                <i data-lucide="clock" style="width:13px;height:13px;"></i>{{ $b['time'] }}
            </span>
        </div>

        {{-- Rating section — muncul jika status = selesai (FR-I1) --}}
        @if($b['status'] === 'selesai')
        <div style="border-top:1px solid #F1F5F9;padding-top:12px;">
            @if($b['rated'])
                {{-- Sudah dirating --}}
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="display:flex;gap:3px;">
                        @for($i=1;$i<=5;$i++)
                            <i data-lucide="star" style="width:15px;height:15px;color:{{ $i<=$b['rating'] ? '#F59E0B':'#CBD5E1' }};fill:{{ $i<=$b['rating']?'#F59E0B':'transparent' }};"></i>
                        @endfor
                    </div>
                    <span style="font-size:0.8125rem;color:#166534;font-weight:600;">Sudah dirating</span>
                    <span style="font-size:0.8125rem;color:#475569;">"{{ $b['review'] }}"</span>
                </div>
            @else
                {{-- Belum dirating — form bintang (FR-I1, FR-I3) --}}
                <div id="rating-{{ $loop->index }}" style="display:flex;flex-direction:column;gap:8px;">
                    <p style="font-size:0.8125rem;font-weight:600;color:#1E293B;margin:0;">Beri penilaian untuk lapangan ini:</p>
                    <div style="display:flex;align-items:center;gap:4px;" id="stars-{{ $loop->index }}">
                        @for($i=1;$i<=5;$i++)
                        <button onclick="setRating({{ $loop->index }}, {{ $i }})"
                                id="star-{{ $loop->index }}-{{ $i }}"
                                style="background:none;border:none;cursor:pointer;padding:2px;">
                            <i data-lucide="star" style="width:22px;height:22px;color:#CBD5E1;"></i>
                        </button>
                        @endfor
                        <span id="rating-label-{{ $loop->index }}" style="font-size:0.8125rem;color:#94A3B8;margin-left:6px;">Pilih bintang</span>
                    </div>
                    <textarea id="review-text-{{ $loop->index }}" class="form-input"
                              style="resize:none;min-height:64px;font-size:0.8125rem;"
                              placeholder="Tulis ulasan singkat (opsional)..."></textarea>
                    <button onclick="submitRating({{ $loop->index }})"
                            class="btn-brand btn-sm" style="align-self:flex-start;">
                        <i data-lucide="send" style="width:14px;height:14px;"></i>
                        Kirim Ulasan
                    </button>
                </div>
            @endif
        </div>
        @endif

        {{-- Upload bukti jika masih menunggu --}}
        @if($b['status'] === 'menunggu_pembayaran')
        <div style="border-top:1px solid #F1F5F9;padding-top:12px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.8125rem;color:#DC2626;display:flex;align-items:center;gap:5px;">
                <i data-lucide="timer" style="width:13px;height:13px;"></i>Segera upload bukti transfer
            </span>
            <a href="/player/booking/payment" class="btn-brand btn-sm">Upload Sekarang</a>
        </div>
        @endif
    </div>
    @endforeach
</div>

@push('scripts')
<script>
    const ratings = {};

    /* Set rating bintang interaktif */
    function setRating(rowIdx, star) {
        ratings[rowIdx] = star;
        const labels = ['','Buruk','Kurang','Cukup','Bagus','Luar Biasa!'];
        document.getElementById('rating-label-' + rowIdx).textContent = labels[star];

        for (let i = 1; i <= 5; i++) {
            const btn = document.getElementById('star-' + rowIdx + '-' + i);
            const icon = btn.querySelector('[data-lucide]');
            icon.style.color = i <= star ? '#F59E0B' : '#CBD5E1';
            icon.style.fill  = i <= star ? '#F59E0B' : 'transparent';
        }
        lucide.createIcons();
    }

    function submitRating(rowIdx) {
        if (!ratings[rowIdx]) {
            alert('Pilih bintang terlebih dahulu!');
            return;
        }
        const container = document.getElementById('rating-' + rowIdx);
        container.innerHTML = `
            <div style="display:flex;align-items:center;gap:8px;color:#166534;">
                <i data-lucide="check-circle" style="width:16px;height:16px;"></i>
                <span style="font-size:0.8125rem;font-weight:600;">Ulasan berhasil dikirim! Terima kasih.</span>
            </div>`;
        lucide.createIcons();
    }
</script>
@endpush

@endsection
