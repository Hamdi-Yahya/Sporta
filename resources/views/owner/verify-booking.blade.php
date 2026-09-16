@extends('layouts.dashboard')

@section('title', 'Verifikasi Booking')
@section('page_title', 'Verifikasi Booking Masuk')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

{{-- ─── Filter tabs ──────────────────────────────────────────── --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
    <a href="{{ route('owner.verify-booking') }}" 
       style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;text-decoration:none;
              border:1.5px solid {{ $status === \App\Models\Booking::STATUS_MENUNGGU_PEMBAYARAN ? '#EA580C' : '#E2E8F0' }};
              background:{{ $status === \App\Models\Booking::STATUS_MENUNGGU_PEMBAYARAN ? '#EA580C' : '#fff' }};
              color:{{ $status === \App\Models\Booking::STATUS_MENUNGGU_PEMBAYARAN ? '#fff' : '#64748B' }};">
        Menunggu Pembayaran ({{ $pendingCount }})
    </a>
    <a href="{{ route('owner.verify-booking', ['status' => \App\Models\Booking::STATUS_TERKONFIRMASI]) }}" 
       style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;text-decoration:none;
              border:1.5px solid {{ $status === \App\Models\Booking::STATUS_TERKONFIRMASI ? '#16A34A' : '#E2E8F0' }};
              background:{{ $status === \App\Models\Booking::STATUS_TERKONFIRMASI ? '#16A34A' : '#fff' }};
              color:{{ $status === \App\Models\Booking::STATUS_TERKONFIRMASI ? '#fff' : '#64748B' }};">
        Terkonfirmasi
    </a>
    <a href="{{ route('owner.verify-booking', ['status' => \App\Models\Booking::STATUS_DITOLAK]) }}" 
       style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;text-decoration:none;
              border:1.5px solid {{ $status === \App\Models\Booking::STATUS_DITOLAK ? '#DC2626' : '#E2E8F0' }};
              background:{{ $status === \App\Models\Booking::STATUS_DITOLAK ? '#DC2626' : '#fff' }};
              color:{{ $status === \App\Models\Booking::STATUS_DITOLAK ? '#fff' : '#64748B' }};">
        Ditolak
    </a>
</div>

{{-- ─── Booking List ───────────────────── --}}
<div style="display:flex;flex-direction:column;gap:14px;margin-bottom:28px;">
    @forelse($bookings as $b)
    @php
        $idStr = '#SPTA-' . str_pad($b->id, 5, '0', STR_PAD_LEFT);
        $tglStr = \Carbon\Carbon::parse($b->slot->tanggal)->translatedFormat('d M Y');
        $waktuStr = \Carbon\Carbon::parse($b->slot->jam_mulai)->format('H:i') . '–' . \Carbon\Carbon::parse($b->slot->jam_selesai)->format('H:i');
        $jenisBooking = $b->tipe_booking === 'biasa' ? 'Booking Biasa' : 'Open Match (kursi '.$b->jumlah_kursi.'/'.$b->slot->kuota_total.')';
    @endphp
    <div class="card" style="padding:20px;border-left:3px solid {{ $status === 'menunggu_pembayaran' ? '#EA580C' : ($status === 'terkonfirmasi' ? '#16A34A' : '#DC2626') }};">
        <div style="display:flex;align-items:flex-start;gap:20px;">

            {{-- Placeholder logo Midtrans atau Bank --}}
            @if($b->pembayaran && $b->pembayaran->bukti_transfer)
            <a href="{{ asset('storage/' . $b->pembayaran->bukti_transfer) }}" target="_blank" 
                 style="width:100px;height:80px;border-radius:8px;background:#F1F5F9;
                        border:2px dashed #CBD5E1;display:flex;align-items:center;
                        justify-content:center;flex-shrink:0;cursor:pointer;overflow:hidden;text-decoration:none;"
                 title="Klik untuk lihat bukti transfer full size">
                <img src="{{ asset('storage/' . $b->pembayaran->bukti_transfer) }}" alt="Bukti" style="width:100%;height:100%;object-fit:cover;">
            </a>
            @elseif($b->sumber_booking === 'offline')
            <div style="width:100px;height:80px;border-radius:8px;background:#F1F5F9;
                        border:2px dashed #CBD5E1;display:flex;align-items:center;
                        justify-content:center;flex-shrink:0;">
                <div style="text-align:center;">
                    <i data-lucide="store" style="width:22px;height:22px;color:#94A3B8;"></i>
                    <div style="font-size:0.625rem;color:#94A3B8;margin-top:3px;">Walk-in</div>
                </div>
            </div>
            @else
            <div style="width:100px;height:80px;border-radius:8px;background:#F1F5F9;
                        border:2px dashed #CBD5E1;display:flex;align-items:center;
                        justify-content:center;flex-shrink:0;">
                <div style="text-align:center;">
                    <i data-lucide="credit-card" style="width:22px;height:22px;color:#94A3B8;"></i>
                    <div style="font-size:0.625rem;color:#94A3B8;margin-top:3px;">Midtrans</div>
                </div>
            </div>
            @endif

            {{-- Info --}}
            <div style="flex:1;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;gap:12px;flex-wrap:wrap;">
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin-bottom:3px;display:flex;align-items:center;gap:6px;">
                            {{ $b->sumber_booking === 'offline' ? $b->nama_pemesan_offline : $b->user->name }}
                            @if($b->sumber_booking === 'offline')
                            <span style="font-size:0.65rem;background:#475569;color:#fff;padding:2px 6px;border-radius:4px;font-weight:600;">OFFLINE</span>
                            @endif
                            <span style="font-weight:400;font-size:0.8125rem;color:#64748B;margin-left:4px;">
                                · {{ $idStr }}
                            </span>
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;margin-bottom:6px;">
                            <i data-lucide="smartphone" style="width:12px;height:12px;display:inline;"></i>
                            {{ $b->sumber_booking === 'offline' ? ($b->no_hp_pemesan_offline ?? '-') : ($b->user->no_hp ?? '-') }}
                            <span style="margin:0 6px;color:#CBD5E1;">•</span>
                            <span style="color:{{ $b->tipe_booking === 'biasa' ? '#64748B' : '#EA580C' }};font-weight:{{ $b->tipe_booking === 'biasa' ? '400' : '600' }};">
                                {{ $jenisBooking }}
                            </span>
                        </div>
                        <div style="display:flex;gap:12px;font-size:0.8125rem;color:#64748B;">
                            <span><i data-lucide="map-pin" style="width:12px;height:12px;display:inline;"></i> {{ $b->slot->lapangan->nama }}</span>
                            <span><i data-lucide="calendar" style="width:12px;height:12px;display:inline;"></i> {{ $tglStr }}</span>
                            <span><i data-lucide="clock" style="width:12px;height:12px;display:inline;"></i> {{ $waktuStr }}</span>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-family:'Poppins',sans-serif;font-size:1.125rem;font-weight:800;color:#166534;">
                            Rp {{ number_format($b->total_harga, 0, ',', '.') }}
                        </div>
                        <div style="font-size:0.75rem;color:#94A3B8;margin-top:2px;">{{ $b->created_at->diffForHumans() }}</div>
                        <x-badge-status :status="$b->status" />
                    </div>
                </div>

                {{-- Action buttons (FR-D3, FR-D4) sudah dihapus karena otomatis by Midtrans --}}
                
                {{-- Jika ada alasan reject dari Midtrans --}}
                @if($status === 'ditolak' && $b->pembayaran && $b->pembayaran->fraud_status)
                <div style="margin-top:12px;padding:12px;background:#FEF2F2;border:1px solid #FECACA;border-radius:6px;font-size:0.8125rem;">
                    <strong>Status Penolakan:</strong> {{ ucfirst($b->pembayaran->transaction_status) }} 
                    ({{ $b->pembayaran->fraud_status }})
                </div>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:48px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;">
        <i data-lucide="check-circle" style="width:40px;height:40px;color:#94A3B8;margin-bottom:12px;opacity:0.5;"></i>
        <p style="font-size:0.9375rem;color:#64748B;margin:0;">Tidak ada booking di status ini.</p>
    </div>
    @endforelse
    
    <div style="margin-top:10px;">
        {{ $bookings->appends(request()->query())->links() }}
    </div>
</div>

@endsection
