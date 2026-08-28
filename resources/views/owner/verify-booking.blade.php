@extends('layouts.dashboard')

@section('title', 'Verifikasi Booking')
@section('page_title', 'Verifikasi Booking Masuk')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

@php
/* Dummy booking yang perlu diverifikasi (FR-D1–D4) */
$bookings = [
    ['id'=>'#SPTA-001', 'user'=>'Ahmad Fauzan',  'phone'=>'08123456789',
     'field'=>'Futsal Planet', 'sport'=>'Futsal', 'date'=>'28 Agt 2026', 'time'=>'16:00–17:00',
     'price'=>'90.000', 'status'=>'menunggu_verifikasi', 'type'=>'Booking Biasa',
     'submitted'=>'10 menit lalu'],
    ['id'=>'#SPTA-002', 'user'=>'Dinda Rahayu',  'phone'=>'08198765432',
     'field'=>'Futsal Planet', 'sport'=>'Futsal', 'date'=>'28 Agt 2026', 'time'=>'18:00–19:00',
     'price'=>'100.000','status'=>'menunggu_verifikasi', 'type'=>'Booking Biasa',
     'submitted'=>'25 menit lalu'],
    ['id'=>'#SPTA-003', 'user'=>'Bima Santoso',  'phone'=>'08156789012',
     'field'=>'Futsal Planet', 'sport'=>'Futsal', 'date'=>'29 Agt 2026', 'time'=>'10:00–11:00',
     'price'=>'9.000',  'status'=>'menunggu_verifikasi', 'type'=>'Open Match (kursi 1/10)',
     'submitted'=>'1 jam lalu'],
    ['id'=>'#SPTA-004', 'user'=>'Rizky Maulana', 'phone'=>'08132145678',
     'field'=>'Futsal Planet', 'sport'=>'Futsal', 'date'=>'29 Agt 2026', 'time'=>'10:00–11:00',
     'price'=>'9.000',  'status'=>'menunggu_verifikasi', 'type'=>'Open Match (kursi 2/10)',
     'submitted'=>'1 jam lalu'],
];

$confirmedBookings = [
    ['id'=>'#SPTA-005','user'=>'Sari W.','date'=>'27 Agt','time'=>'07:00–08:00','price'=>'80.000','status'=>'terkonfirmasi'],
    ['id'=>'#SPTA-006','user'=>'Joko P.','date'=>'27 Agt','time'=>'14:00–15:00','price'=>'90.000','status'=>'terkonfirmasi'],
    ['id'=>'#SPTA-007','user'=>'Maya K.','date'=>'26 Agt','time'=>'16:00–17:00','price'=>'90.000','status'=>'ditolak'],
];
@endphp

{{-- ─── Filter tabs ──────────────────────────────────────────── --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;align-items:center;">
    <button style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;
                   border:1.5px solid #EA580C;background:#EA580C;color:#fff;">
        Menunggu Verifikasi ({{ count($bookings) }})
    </button>
    <button style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;
                   border:1.5px solid #E2E8F0;background:#fff;color:#64748B;">
        Terkonfirmasi
    </button>
    <button style="padding:7px 16px;border-radius:20px;font-size:0.8125rem;font-weight:600;cursor:pointer;
                   border:1.5px solid #E2E8F0;background:#fff;color:#64748B;">
        Ditolak
    </button>
</div>

{{-- ─── Booking yang perlu diverifikasi ───────────────────── --}}
<div style="display:flex;flex-direction:column;gap:14px;margin-bottom:28px;">
    @foreach($bookings as $b)
    <div class="card" style="padding:20px;border-left:3px solid #EA580C;">
        <div style="display:flex;align-items:flex-start;gap:20px;">

            {{-- Placeholder foto bukti --}}
            <div style="width:100px;height:80px;border-radius:8px;background:#F1F5F9;
                        border:2px dashed #CBD5E1;display:flex;align-items:center;
                        justify-content:center;flex-shrink:0;cursor:pointer;"
                 title="Klik untuk lihat bukti transfer full size">
                <div style="text-align:center;">
                    <i data-lucide="image" style="width:22px;height:22px;color:#94A3B8;"></i>
                    <div style="font-size:0.625rem;color:#94A3B8;margin-top:3px;">Bukti Transfer</div>
                </div>
            </div>

            {{-- Info --}}
            <div style="flex:1;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;gap:12px;">
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin-bottom:3px;">
                            {{ $b['user'] }}
                            <span style="font-weight:400;font-size:0.8125rem;color:#64748B;margin-left:4px;">
                                · {{ $b['id'] }}
                            </span>
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;margin-bottom:6px;">
                            <i data-lucide="smartphone" style="width:12px;height:12px;display:inline;"></i>
                            {{ $b['phone'] }}
                            <span style="margin:0 6px;color:#CBD5E1;">•</span>
                            <span style="color:{{ $b['type'] === 'Booking Biasa' ? '#64748B' : '#EA580C' }};font-weight:{{ $b['type'] === 'Booking Biasa' ? '400' : '600' }};">
                                {{ $b['type'] }}
                            </span>
                        </div>
                        <div style="display:flex;gap:12px;font-size:0.8125rem;color:#64748B;">
                            <span><i data-lucide="calendar" style="width:12px;height:12px;display:inline;"></i> {{ $b['date'] }}</span>
                            <span><i data-lucide="clock" style="width:12px;height:12px;display:inline;"></i> {{ $b['time'] }}</span>
                        </div>
                    </div>
                    <div style="text-align:right;flex-shrink:0;">
                        <div style="font-family:'Poppins',sans-serif;font-size:1.125rem;font-weight:800;color:#166534;">
                            Rp {{ $b['price'] }}
                        </div>
                        <div style="font-size:0.75rem;color:#94A3B8;margin-top:2px;">{{ $b['submitted'] }}</div>
                        <x-badge-status :status="$b['status']" />
                    </div>
                </div>

                {{-- Action buttons (FR-D3, FR-D4) --}}
                <div style="display:flex;gap:8px;padding-top:12px;border-top:1px solid #F1F5F9;">
                    <button onclick="confirmBooking('{{ $b['id'] }}')"
                            class="btn-brand btn-sm">
                        <i data-lucide="check" style="width:13px;height:13px;"></i>
                        Setujui Booking
                    </button>
                    <button onclick="document.getElementById('reject-modal-{{ $loop->index }}').style.display='flex'"
                            class="btn-brand btn-sm btn-danger">
                        <i data-lucide="x" style="width:13px;height:13px;"></i>
                        Tolak
                    </button>
                    <button style="padding:6px 12px;background:none;border:1.5px solid #E2E8F0;
                                  border-radius:6px;font-size:0.8125rem;font-weight:600;color:#64748B;cursor:pointer;">
                        <i data-lucide="zoom-in" style="width:13px;height:13px;display:inline;"></i>
                        Lihat Bukti
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tolak Booking (FR-D4) --}}
    <div id="reject-modal-{{ $loop->index }}"
         style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;
                align-items:center;justify-content:center;padding:20px;">
        <div style="background:#fff;border-radius:12px;width:100%;max-width:420px;padding:24px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#1E293B;margin:0 0 14px;">
                Tolak Booking {{ $b['id'] }}
            </h3>
            <p style="font-size:0.875rem;color:#64748B;margin:0 0 14px;">
                Berikan alasan penolakan. Alasan ini akan dikirim ke pemesan sebagai notifikasi.
            </p>
            <label class="form-label">Alasan Penolakan</label>
            <textarea class="form-input" style="resize:none;min-height:90px;margin-bottom:14px;"
                      placeholder="Contoh: Bukti transfer tidak terbaca, nominal tidak sesuai, dll."></textarea>
            <div style="display:flex;gap:10px;justify-content:flex-end;">
                <button onclick="document.getElementById('reject-modal-{{ $loop->index }}').style.display='none'"
                        class="btn-outline btn-sm">Batal</button>
                <button class="btn-brand btn-sm btn-danger">
                    <i data-lucide="x-circle" style="width:13px;height:13px;"></i>
                    Konfirmasi Tolak
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ─── Riwayat booking yang sudah diproses ────────────────── --}}
<h3 class="section-title" style="font-size:0.9375rem;margin-bottom:14px;">Riwayat Verifikasi</h3>
<div class="card" style="overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:0.8125rem;">
        <thead>
            <tr style="background:#F8FAFC;border-bottom:1px solid #E2E8F0;">
                <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">ID</th>
                <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Pemesan</th>
                <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Jadwal</th>
                <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Total</th>
                <th style="text-align:left;padding:10px 14px;font-weight:600;color:#64748B;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($confirmedBookings as $cb)
            <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="padding:10px 14px;color:#94A3B8;font-size:0.75rem;">{{ $cb['id'] }}</td>
                <td style="padding:10px 14px;font-weight:600;color:#1E293B;">{{ $cb['user'] }}</td>
                <td style="padding:10px 14px;color:#475569;">{{ $cb['date'] }} · {{ $cb['time'] }}</td>
                <td style="padding:10px 14px;font-weight:700;color:#166534;">Rp {{ $cb['price'] }}</td>
                <td style="padding:10px 14px;"><x-badge-status :status="$cb['status']" /></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@push('scripts')
<script>
    function confirmBooking(id) {
        if (confirm('Setujui booking ' + id + '? Notifikasi akan dikirim ke pemesan.')) {
            alert('Booking ' + id + ' berhasil dikonfirmasi!');
        }
    }
</script>
@endpush

@endsection
