@extends('layouts.dashboard')

@section('title', 'Kelola Booking Offline')
@section('page_title', 'Kelola Booking Offline')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

@if(session('success'))
    <div style="background:#DCFCE7;border:1px solid #BBF7D0;color:#166534;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:0.875rem;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background:#FEE2E2;border:1px solid #FECACA;color:#991B1B;padding:12px 16px;border-radius:8px;margin-bottom:20px;font-size:0.875rem;">
        {{ session('error') }}
    </div>
@endif

{{-- ─── Date picker & field selector ─────────────────────── --}}
<form method="GET" action="{{ route('owner.booking-offline') }}" id="filter-form">
<div class="card" style="padding:16px 20px;margin-bottom:20px;
            display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
    <div>
        <label class="form-label" style="margin-bottom:4px;">Pilih Lapangan</label>
        <select name="lapangan_id" class="form-input" style="width:auto;padding:8px 12px;font-size:0.875rem;"
                onchange="this.form.submit()">
            <option value="">Pilih Lapangan</option>
            @foreach($lapangans as $lap)
                <option value="{{ $lap->id }}" {{ request('lapangan_id') == $lap->id ? 'selected' : '' }}>
                    {{ $lap->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label" style="margin-bottom:4px;">Tanggal</label>
        <input type="date" name="tanggal" class="form-input" 
               value="{{ $selectedTanggal }}" 
               style="width:auto;"
               onchange="this.form.submit()">
    </div>
</div>
</form>

{{-- ─── Slot Tersedia Grid ────────────────────────────────────────── --}}
<div style="margin-bottom:14px;">
    <h3 class="section-title" style="font-size:1rem;margin:0;">Pilih Slot Tersedia</h3>
    <p style="font-size:0.8125rem;color:#64748B;margin:4px 0 0;">Pilih slot yang akan dibooking secara offline (walk-in/telepon).</p>
</div>

<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px;">
    @if(isset($slots) && $slots->count() > 0)
        @php
            // Filter hanya slot tersedia
            $availableSlots = $slots->filter(function($s) { return $s->status === 'tersedia'; });
        @endphp

        @if($availableSlots->count() > 0)
            @foreach($availableSlots as $slot)
            <div style="border:1.5px solid #E2E8F0;background:#fff;border-radius:8px;
                        padding:14px 12px;position:relative;text-align:center;
                        display:flex;flex-direction:column;justify-content:center;gap:6px;">

                <div style="font-size:0.875rem;font-weight:700;color:#1E293B;">
                    {{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i') }}
                </div>
                
                <div style="font-size:0.75rem;color:#16A34A;font-weight:600;">Tersedia</div>
                
                @if($slot->tipe === 'open_match')
                    <div style="font-size:0.65rem;color:#EA580C;background:#FFF7ED;border:1px solid #FED7AA;border-radius:4px;padding:2px 4px;">
                        Open Match (Akan diambil alih penuh)
                    </div>
                @endif
                
                <button type="button" class="btn-brand btn-sm" style="margin-top:4px;width:100%;"
                        onclick="openBookingModal('{{ $slot->id }}', '{{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i') }}')">
                    Pilih
                </button>
            </div>
            @endforeach
        @else
            <div style="grid-column:1/-1;text-align:center;padding:48px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;">
                <p style="font-size:0.9375rem;color:#64748B;margin:0;">
                    Semua slot sudah terpesan atau tidak ada slot pada tanggal ini.
                </p>
            </div>
        @endif
    @else
        <div style="grid-column:1/-1;text-align:center;padding:48px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;">
            <i data-lucide="calendar-off" style="width:36px;height:36px;color:#94A3B8;margin-bottom:10px;opacity:0.6;"></i>
            @if($selectedLapangan)
                <p style="font-size:0.9375rem;color:#64748B;margin:0;">
                    Belum ada slot untuk tanggal ini.
                </p>
            @else
                <p style="font-size:0.9375rem;color:#64748B;margin:0;">
                    Pilih lapangan terlebih dahulu untuk melihat slot tersedia.
                </p>
            @endif
        </div>
    @endif
</div>

{{-- ─── Modal: Form Booking Offline ───────────────────── --}}
<div id="booking-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;
            align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:12px;width:100%;max-width:460px;">
        <div style="padding:18px 22px;border-bottom:1px solid #F1F5F9;
                    display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin:0;">
                Detail Booking Offline
            </h3>
            <button onclick="document.getElementById('booking-modal').style.display='none'"
                    style="background:none;border:none;cursor:pointer;color:#94A3B8;">
                <i data-lucide="x" style="width:18px;height:18px;"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('owner.booking-offline.store') }}">
        @csrf
        <input type="hidden" name="slot_id" id="modal-slot-id">
        <div style="padding:22px;display:flex;flex-direction:column;gap:14px;">
            
            <div style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:6px;padding:10px 14px;font-size:0.8125rem;">
                Waktu: <strong id="modal-slot-time"></strong>
            </div>

            <div>
                <label class="form-label">Nama Pemesan <span style="color:#DC2626">*</span></label>
                <input type="text" name="nama_pemesan_offline" class="form-input" placeholder="Masukkan nama (wajib)" required>
            </div>
            
            <div>
                <label class="form-label">Nomor HP / WhatsApp (Opsional)</label>
                <input type="text" name="no_hp_pemesan_offline" class="form-input" placeholder="Contoh: 08123456789">
            </div>

            <div>
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="catatan_offline" class="form-input" rows="3" placeholder="Tulis catatan jika ada..."></textarea>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #F1F5F9;">
                <button type="button" onclick="document.getElementById('booking-modal').style.display='none'"
                        class="btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn-brand btn-sm">
                    <i data-lucide="check" style="width:13px;height:13px;"></i>
                    Konfirmasi Booking
                </button>
            </div>
        </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openBookingModal(slotId, timeString) {
        document.getElementById('modal-slot-id').value = slotId;
        document.getElementById('modal-slot-time').textContent = timeString;
        document.getElementById('booking-modal').style.display = 'flex';
    }
</script>
@endpush

@endsection
