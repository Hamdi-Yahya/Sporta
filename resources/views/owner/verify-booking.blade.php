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
              border:1.5px solid {{ $status === \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI ? '#EA580C' : '#E2E8F0' }};
              background:{{ $status === \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI ? '#EA580C' : '#fff' }};
              color:{{ $status === \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI ? '#fff' : '#64748B' }};">
        Menunggu Verifikasi ({{ $pendingCount }})
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
    <div class="card" style="padding:20px;border-left:3px solid {{ $status === 'menunggu_verifikasi' ? '#EA580C' : ($status === 'terkonfirmasi' ? '#16A34A' : '#DC2626') }};">
        <div style="display:flex;align-items:flex-start;gap:20px;">

            {{-- Placeholder foto bukti --}}
            @if($b->pembayaran && $b->pembayaran->bukti_transfer)
            <a href="{{ asset('storage/' . $b->pembayaran->bukti_transfer) }}" target="_blank" 
                 style="width:100px;height:80px;border-radius:8px;background:#F1F5F9;
                        border:2px dashed #CBD5E1;display:flex;align-items:center;
                        justify-content:center;flex-shrink:0;cursor:pointer;overflow:hidden;text-decoration:none;"
                 title="Klik untuk lihat bukti transfer full size">
                <img src="{{ asset('storage/' . $b->pembayaran->bukti_transfer) }}" alt="Bukti" style="width:100%;height:100%;object-fit:cover;">
            </a>
            @else
            <div style="width:100px;height:80px;border-radius:8px;background:#F1F5F9;
                        border:2px dashed #CBD5E1;display:flex;align-items:center;
                        justify-content:center;flex-shrink:0;">
                <div style="text-align:center;">
                    <i data-lucide="image-off" style="width:22px;height:22px;color:#94A3B8;"></i>
                    <div style="font-size:0.625rem;color:#94A3B8;margin-top:3px;">No Bukti</div>
                </div>
            </div>
            @endif

            {{-- Info --}}
            <div style="flex:1;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;gap:12px;flex-wrap:wrap;">
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin-bottom:3px;">
                            {{ $b->user->name }}
                            <span style="font-weight:400;font-size:0.8125rem;color:#64748B;margin-left:4px;">
                                · {{ $idStr }}
                            </span>
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;margin-bottom:6px;">
                            <i data-lucide="smartphone" style="width:12px;height:12px;display:inline;"></i>
                            {{ $b->user->no_hp ?? '-' }}
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

                {{-- Action buttons (FR-D3, FR-D4) hanya jika menunggu verifikasi --}}
                @if($status === 'menunggu_verifikasi')
                <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #F1F5F9;flex-wrap:wrap;">
                    <form action="{{ route('owner.verify-booking.approve', $b->id) }}" method="POST" onsubmit="return confirm('Setujui booking ini?')">
                        @csrf
                        <button type="submit" class="btn-brand btn-sm">
                            <i data-lucide="check" style="width:13px;height:13px;"></i>
                            Setujui Booking
                        </button>
                    </form>
                    
                    <button type="button" onclick="document.getElementById('reject-modal-{{ $b->id }}').style.display='flex'"
                            class="btn-brand btn-sm btn-danger">
                        <i data-lucide="x" style="width:13px;height:13px;"></i>
                        Tolak
                    </button>
                    
                    @if($b->pembayaran && $b->pembayaran->bukti_transfer)
                    <a href="{{ asset('storage/' . $b->pembayaran->bukti_transfer) }}" target="_blank" 
                       style="padding:6px 12px;background:none;border:1.5px solid #E2E8F0;
                                   border-radius:6px;font-size:0.8125rem;font-weight:600;color:#64748B;cursor:pointer;text-decoration:none;">
                        <i data-lucide="zoom-in" style="width:13px;height:13px;display:inline;"></i>
                        Lihat Bukti
                    </a>
                    @endif
                </div>

                {{-- Modal Tolak Booking (FR-D4) --}}
                <div id="reject-modal-{{ $b->id }}"
                     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;
                            align-items:center;justify-content:center;padding:20px;">
                    <form action="{{ route('owner.verify-booking.reject', $b->id) }}" method="POST" style="background:#fff;border-radius:12px;width:100%;max-width:420px;padding:24px;">
                        @csrf
                        <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#1E293B;margin:0 0 14px;">
                            Tolak Booking {{ $idStr }}
                        </h3>
                        <p style="font-size:0.875rem;color:#64748B;margin:0 0 14px;">
                            Berikan alasan penolakan. Alasan ini akan dikirim ke pemesan sebagai notifikasi.
                        </p>
                        <label class="form-label">Alasan Penolakan</label>
                        <textarea name="alasan" class="form-input" style="resize:none;min-height:90px;margin-bottom:14px;"
                                  placeholder="Contoh: Bukti transfer tidak terbaca, nominal tidak sesuai, dll." required></textarea>
                        <div style="display:flex;gap:10px;justify-content:flex-end;">
                            <button type="button" onclick="document.getElementById('reject-modal-{{ $b->id }}').style.display='none'"
                                    class="btn-outline btn-sm">Batal</button>
                            <button type="submit" class="btn-brand btn-sm btn-danger">
                                <i data-lucide="x-circle" style="width:13px;height:13px;"></i>
                                Konfirmasi Tolak
                            </button>
                        </div>
                    </form>
                </div>
                @endif
                
                {{-- Jika ada alasan reject --}}
                @if($status === 'ditolak' && $b->pembayaran && $b->pembayaran->catatan_reject)
                <div style="margin-top:12px;padding:12px;background:#FEF2F2;border:1px solid #FECACA;border-radius:6px;font-size:0.8125rem;">
                    <strong>Alasan Ditolak:</strong> {{ $b->pembayaran->catatan_reject }}
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
