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
// $bookings is passed from BookingController
@endphp

{{-- ─── Filter tabs ────────────────────────────────────── --}}
@php
$statusMap = [
    'Semua'                => '',
    'Terkonfirmasi'        => 'terkonfirmasi',
    'Menunggu Pembayaran'  => 'menunggu_pembayaran',
    'Selesai'              => 'selesai',
    'Ditolak/Expired'      => 'ditolak',
];
$activeStatus = request('status', '');
@endphp
<div style="display:flex;gap:6px;margin-bottom:20px;flex-wrap:wrap;">
    @foreach($statusMap as $label => $value)
    @php $isActive = $activeStatus === $value; @endphp
    <a href="{{ route('player.history') . ($value ? '?status='.$value : '') }}"
       style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;
              text-decoration:none;white-space:nowrap;
              border:1.5px solid {{ $isActive ? '#16A34A' : '#E2E8F0' }};
              background:{{ $isActive ? '#16A34A' : '#fff' }};
              color:{{ $isActive ? '#fff' : '#64748B' }};
              transition:background 0.15s;">
        {{ $label }}
    </a>
    @endforeach
</div>

{{-- ─── Riwayat List ─────────────────────────────────────────── --}}
<div style="display:flex;flex-direction:column;gap:14px;">
    @forelse($bookings as $b)
    @php
        $idBooking = '#SPTA-' . str_pad($b->id, 5, '0', STR_PAD_LEFT);
        $lapangan = $b->slot->lapangan;
        $tglStr = \Carbon\Carbon::parse($b->slot->tanggal)->translatedFormat('d M Y');
        $waktuStr = \Carbon\Carbon::parse($b->slot->jam_mulai)->format('H:i') . '–' . \Carbon\Carbon::parse($b->slot->jam_selesai)->format('H:i');
    @endphp
    <div class="card" style="padding:18px 20px;">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;gap:12px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                               color:#1E293B;margin:0;">{{ $lapangan->nama }}</h3>
                    <x-badge-status :status="$b->status" />
                </div>
                <div style="font-size:0.8125rem;color:#64748B;">{{ $lapangan->cabangOlahraga->nama_cabor }}</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#166534;">
                    Rp {{ number_format($b->total_harga, 0, ',', '.') }}
                </div>
                <div style="font-size:0.75rem;color:#94A3B8;">{{ $idBooking }}</div>
            </div>
        </div>

        <div style="display:flex;gap:16px;font-size:0.8125rem;color:#64748B;margin-bottom:12px;flex-wrap:wrap;">
            <span style="display:flex;align-items:center;gap:4px;">
                <i data-lucide="calendar" style="width:13px;height:13px;"></i>{{ $tglStr }}
            </span>
            <span style="display:flex;align-items:center;gap:4px;">
                <i data-lucide="clock" style="width:13px;height:13px;"></i>{{ $waktuStr }}
            </span>
        </div>

        {{-- Rating section — muncul jika status = selesai (FR-I1) --}}
        @if($b->status === 'selesai')
        <div style="border-top:1px solid #F1F5F9;padding-top:12px;">
            @if($b->rating)
                {{-- Sudah dirating --}}
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="display:flex;gap:3px;">
                        @for($i=1;$i<=5;$i++)
                            <i data-lucide="star" style="width:15px;height:15px;color:{{ $i<=$b->rating->rating ? '#F59E0B':'#CBD5E1' }};fill:{{ $i<=$b->rating->rating?'#F59E0B':'transparent' }};"></i>
                        @endfor
                    </div>
                    <span style="font-size:0.8125rem;color:#166534;font-weight:600;">Sudah dirating</span>
                    <span style="font-size:0.8125rem;color:#475569;">"{{ $b->rating->ulasan }}"</span>
                </div>
            @else
                {{-- Belum dirating — form bintang (FR-I1, FR-I3) --}}
                <form action="{{ route('player.rating.store') }}" method="POST" id="rating-{{ $loop->index }}" style="display:flex;flex-direction:column;gap:8px;">
                    @csrf
                    <input type="hidden" name="booking_id" value="{{ $b->id }}">
                    <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">
                    <input type="hidden" name="rating" id="rating-input-{{ $loop->index }}" value="0">
                    
                    <p style="font-size:0.8125rem;font-weight:600;color:#1E293B;margin:0;">Beri penilaian untuk lapangan ini:</p>
                    <div style="display:flex;align-items:center;gap:4px;" id="stars-{{ $loop->index }}">
                        @for($i=1;$i<=5;$i++)
                        <button type="button" onclick="setRating({{ $loop->index }}, {{ $i }})"
                                id="star-{{ $loop->index }}-{{ $i }}"
                                style="background:none;border:none;cursor:pointer;padding:2px;">
                            <i data-lucide="star" style="width:22px;height:22px;color:#CBD5E1;"></i>
                        </button>
                        @endfor
                        <span id="rating-label-{{ $loop->index }}" style="font-size:0.8125rem;color:#94A3B8;margin-left:6px;">Pilih bintang</span>
                    </div>
                    <textarea name="ulasan" id="review-text-{{ $loop->index }}" class="form-input"
                              style="resize:none;min-height:64px;font-size:0.8125rem;"
                              placeholder="Tulis ulasan singkat (opsional)..."></textarea>
                    <button type="submit" class="btn-brand btn-sm" style="align-self:flex-start;">
                        <i data-lucide="send" style="width:14px;height:14px;"></i>
                        Kirim Ulasan
                    </button>
                </form>
            @endif
        </div>
        @endif

        {{-- Upload bukti jika masih menunggu --}}
        @if($b->status === 'menunggu_pembayaran')
        <div style="border-top:1px solid #F1F5F9;padding-top:12px;display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.8125rem;color:#DC2626;display:flex;align-items:center;gap:5px;">
                <i data-lucide="timer" style="width:13px;height:13px;"></i>Segera upload bukti transfer
            </span>
            <a href="{{ route('player.booking.payment', $b->id) }}" class="btn-brand btn-sm">Upload Sekarang</a>
        </div>
        @endif
    </div>
    @empty
    <div style="text-align:center;padding:40px;background:#F8FAFC;border-radius:12px;border:1px dashed #E2E8F0;">
        <i data-lucide="file-text" style="width:40px;height:40px;color:#94A3B8;margin-bottom:12px;opacity:0.5;"></i>
        <p style="font-size:0.9375rem;color:#64748B;margin:0;">Belum ada riwayat booking.</p>
    </div>
    @endforelse
    
    <div style="margin-top:20px;">
        {{ $bookings->links() }}
    </div>
</div>

@push('scripts')
<script>
    const ratings = {};

    /* Set rating bintang interaktif */
    function setRating(rowIdx, star) {
        ratings[rowIdx] = star;
        const labels = ['','Buruk','Kurang','Cukup','Bagus','Luar Biasa!'];
        document.getElementById('rating-label-' + rowIdx).textContent = labels[star];
        
        // Simpan ke hidden input
        document.getElementById('rating-input-' + rowIdx).value = star;

        for (let i = 1; i <= 5; i++) {
            const btn = document.getElementById('star-' + rowIdx + '-' + i);
            const icon = btn.querySelector('[data-lucide]');
            icon.style.color = i <= star ? '#F59E0B' : '#CBD5E1';
            icon.style.fill  = i <= star ? '#F59E0B' : 'transparent';
        }
        lucide.createIcons();
    }
</script>
@endpush

@endsection
