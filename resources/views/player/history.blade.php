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

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:10px;">
            <div style="display:flex;gap:16px;font-size:0.8125rem;color:#64748B;flex-wrap:wrap;">
                <span style="display:flex;align-items:center;gap:4px;">
                    <i data-lucide="calendar" style="width:13px;height:13px;"></i>{{ $tglStr }}
                </span>
                <span style="display:flex;align-items:center;gap:4px;">
                    <i data-lucide="clock" style="width:13px;height:13px;"></i>{{ $waktuStr }}
                </span>
            </div>
            
            @if(in_array($b->status, ['terkonfirmasi', 'selesai']))
            <button type="button" onclick="showReceipt('{{ $idBooking }}', '{{ addslashes(Auth::user()->name) }}', '{{ addslashes($lapangan->nama) }}', '{{ addslashes($lapangan->cabangOlahraga->nama_cabor) }}', '{{ $tglStr }}', '{{ $waktuStr }}', '{{ number_format($b->total_harga, 0, ',', '.') }}', 'Lunas', '{{ ucfirst($b->status) }}')" 
                    class="btn-outline btn-sm" 
                    style="display:flex;align-items:center;gap:5px;padding:4px 10px;font-size:0.75rem;cursor:pointer;">
                <i data-lucide="receipt" style="width:14px;height:14px;"></i> Bukti Booking
            </button>
            @endif
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

{{-- ─── Modal Nota Booking ─── --}}
<div id="receipt-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:999;align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;width:100%;max-width:400px;border-radius:12px;overflow:hidden;box-shadow:0 10px 25px rgba(0,0,0,0.15);">
        <div id="receipt-content" style="padding:24px;">
            <div style="text-align:center;border-bottom:2px dashed #E2E8F0;padding-bottom:16px;margin-bottom:16px;">
                <h2 style="font-family:'Poppins',sans-serif;font-weight:800;font-size:1.5rem;color:#16A34A;margin:0;">SPORTA</h2>
                <div style="font-size:0.8125rem;color:#64748B;margin-top:4px;font-weight:600;">BUKTI PEMESANAN LAPANGAN</div>
            </div>
            
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem;">
                <span style="color:#64748B;">No. Booking</span>
                <strong id="rcpt-id" style="color:#1E293B;">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem;">
                <span style="color:#64748B;">Nama Pemesan</span>
                <strong id="rcpt-name" style="color:#1E293B;text-align:right;">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem;">
                <span style="color:#64748B;">Lapangan</span>
                <strong id="rcpt-field" style="color:#1E293B;text-align:right;max-width:180px;">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem;">
                <span style="color:#64748B;">Cabang Olahraga</span>
                <strong id="rcpt-sport" style="color:#1E293B;">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem;">
                <span style="color:#64748B;">Jadwal</span>
                <strong id="rcpt-date" style="color:#1E293B;text-align:right;">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:0.875rem;">
                <span style="color:#64748B;">Status Pemb.</span>
                <strong id="rcpt-paystatus" style="color:#166534;">-</strong>
            </div>
            <div style="display:flex;justify-content:space-between;margin-bottom:16px;font-size:0.875rem;">
                <span style="color:#64748B;">Status Booking</span>
                <strong id="rcpt-bookstatus" style="color:#16A34A;">-</strong>
            </div>
            
            <div style="border-top:2px dashed #E2E8F0;padding-top:16px;display:flex;justify-content:space-between;align-items:center;">
                <span style="font-size:0.9375rem;font-weight:600;color:#1E293B;">Total Bayar</span>
                <strong id="rcpt-total" style="font-size:1.125rem;color:#166534;font-family:'Poppins',sans-serif;">-</strong>
            </div>
        </div>
        
        <div style="padding:16px 24px;background:#F8FAFC;display:flex;gap:12px;justify-content:space-between;border-top:1px solid #E2E8F0;">
            <button type="button" onclick="document.getElementById('receipt-modal').style.display='none'" class="btn-outline" style="flex:1;cursor:pointer;background:#fff;">Tutup</button>
            <button type="button" onclick="printReceipt()" class="btn-brand" style="flex:1;display:flex;align-items:center;justify-content:center;gap:6px;cursor:pointer;">
                <i data-lucide="printer" style="width:16px;height:16px;"></i> Cetak Nota
            </button>
        </div>
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

    /* Tampilkan modal Nota Booking */
    function showReceipt(id, name, field, sport, date, time, total, paystatus, bookstatus) {
        document.getElementById('rcpt-id').innerText = id;
        document.getElementById('rcpt-name').innerText = name;
        document.getElementById('rcpt-field').innerText = field;
        document.getElementById('rcpt-sport').innerText = sport;
        document.getElementById('rcpt-date').innerText = date + ', ' + time;
        document.getElementById('rcpt-paystatus').innerText = paystatus;
        document.getElementById('rcpt-bookstatus').innerText = bookstatus;
        document.getElementById('rcpt-total').innerText = 'Rp ' + total;
        
        document.getElementById('receipt-modal').style.display = 'flex';
    }

    /* Cetak area nota saja */
    function printReceipt() {
        const printContent = document.getElementById('receipt-content').innerHTML;
        const printWindow = window.open('', '_blank', 'height=600,width=450');
        printWindow.document.write(`
            <html>
            <head>
                <title>Bukti Booking SPORTA</title>
                <style>
                    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #1E293B; background: #fff; }
                    .print-container { max-width: 400px; margin: 0 auto; border: 1px solid #E2E8F0; border-radius: 8px; padding: 24px; }
                    * { box-sizing: border-box; }
                </style>
            </head>
            <body>
                <div class="print-container">
                    ${printContent}
                </div>
                <script>
                    window.onload = function() { window.print(); window.close(); }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }
</script>
@endpush

@endsection
