@extends('layouts.dashboard')

@section('title', 'Kelola Jadwal & Slot')
@section('page_title', 'Kelola Jadwal & Slot')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

@php
$bookedCount = 0;
$availableCount = 0;
$openMatchCount = 0;
if (isset($slots) && $slots->count() > 0) {
    $bookedCount = $slots->whereIn('status', ['dibooking', 'penuh'])->count();
    $availableCount = $slots->where('status', 'tersedia')->count();
    $openMatchCount = $slots->where('tipe', 'open_match')->count();
}
@endphp

{{-- ─── Date picker & field selector ─────────────────────── --}}
<form method="GET" action="{{ route('owner.schedules') }}" id="filter-form">
<div class="card" style="padding:16px 20px;margin-bottom:20px;
            display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
    <div>
        <label class="form-label" style="margin-bottom:4px;">Lapangan</label>
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
    <div style="margin-left:auto;display:flex;gap:10px;align-items:flex-end;">
        @if($selectedLapangan)
        <button type="button" onclick="document.getElementById('add-slot-modal').style.display='flex'"
                class="btn-brand">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            Tambah Slot
        </button>
        @endif
    </div>
</div>
</form>

{{-- ─── Stats bar ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
    @foreach([
        ['label'=>'Total Slot',          'val'=>isset($slots) ? $slots->count() : 0, 'color'=>'#1E293B'],
        ['label'=>'Terpesan',            'val'=>$bookedCount,  'color'=>'#16A34A'],
        ['label'=>'Masih Tersedia',      'val'=>$availableCount,'color'=>'#64748B'],
    ] as $s)
    <div class="stat-card" style="padding:14px 18px;">
        <div style="font-size:0.8125rem;color:#64748B;margin-bottom:4px;">{{ $s['label'] }}</div>
        <div style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:{{ $s['color'] }};">{{ $s['val'] }}</div>
    </div>
    @endforeach
</div>

{{-- ─── Legend ──────────────────────────────────────────────── --}}
<div style="display:flex;gap:16px;margin-bottom:14px;font-size:0.75rem;flex-wrap:wrap;">
    <span style="display:flex;align-items:center;gap:5px;color:#64748B;">
        <span style="width:14px;height:14px;border:1.5px solid #CBD5E1;border-radius:3px;"></span>Tersedia
    </span>
    <span style="display:flex;align-items:center;gap:5px;color:#166534;font-weight:600;">
        <span style="width:14px;height:14px;background:#DCFCE7;border:1.5px solid #BBF7D0;border-radius:3px;"></span>Terpesan
    </span>
    <span style="display:flex;align-items:center;gap:5px;color:#EA580C;font-weight:600;">
        <span style="width:14px;height:14px;background:#FFF7ED;border:1.5px solid #FED7AA;border-radius:3px;"></span>Open Match
    </span>
</div>

{{-- ─── Slot grid (§3.7, FR-B4–B6) ───────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:10px;">
    @if(isset($slots) && $slots->count() > 0)
        @foreach($slots as $slot)
        @php
            $bg = '#fff';
            $border = '#E2E8F0';
            $textColor = '#1E293B';
            $isBooked = in_array($slot->status, ['dibooking', 'penuh']);

            if ($slot->tipe === 'open_match') {
                $bg = '#FFF7ED'; $border = '#FED7AA'; $textColor = '#9A3412';
            } elseif ($isBooked) {
                $bg = '#DCFCE7'; $border = '#BBF7D0'; $textColor = '#166534';
            }
        @endphp
        <div style="border:1.5px solid {{ $border }};background:{{ $bg }};border-radius:8px;
                    padding:10px 12px;position:relative;min-height:80px;">

            <div style="font-size:0.75rem;font-weight:700;color:{{ $textColor }};margin-bottom:3px;">
                {{ \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i') }}
            </div>

            @if($slot->tipe === 'open_match')
                <div style="font-size:0.65rem;font-weight:700;color:#EA580C;background:#FED7AA;
                            border-radius:3px;padding:1px 5px;display:inline-block;margin-bottom:4px;">
                    OPEN MATCH
                </div>
                <div style="font-size:0.7rem;color:#9A3412;">
                    {{ $slot->kuota_terisi ?? 0 }}/{{ $slot->kuota_total }} Pemain<br>
                    Rp {{ number_format($slot->hargaPerKursi(), 0, ',', '.') }}/org
                </div>
                <div class="progress-bar" style="margin-top:5px;">
                    <div class="progress-bar-fill" style="width:{{ ($slot->kuota_total > 0 ? (($slot->kuota_terisi ?? 0)/$slot->kuota_total)*100 : 0) }}%;"></div>
                </div>
            @elseif($isBooked)
                <div style="font-size:0.7rem;color:#166534;">
                    Terpesan
                </div>
            @else
                <div style="font-size:0.7rem;color:#94A3B8;">Tersedia</div>
                <div style="font-size:0.7rem;color:#64748B;">Rp {{ number_format($slot->harga, 0, ',', '.') }}</div>
            @endif

            {{-- Action buttons (hanya untuk slot available) --}}
            @if($slot->status === 'tersedia')
            <div style="position:absolute;bottom:6px;right:6px;display:flex;gap:4px;">
                <form action="{{ route('owner.schedules.destroy', $slot->id) }}" method="POST" onsubmit="return confirm('Hapus slot ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:none;border:none;cursor:pointer;color:#DC2626;padding:2px;" title="Hapus slot">
                        <i data-lucide="trash-2" style="width:11px;height:11px;"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    @else
        <div style="grid-column:1/-1;text-align:center;padding:48px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;">
            <i data-lucide="calendar-off" style="width:36px;height:36px;color:#94A3B8;margin-bottom:10px;opacity:0.6;"></i>
            @if($selectedLapangan)
                <p style="font-size:0.9375rem;color:#64748B;margin:0 0 8px;">
                    Belum ada slot untuk tanggal ini.
                </p>
                <p style="font-size:0.8125rem;color:#94A3B8;margin:0;">
                    Klik <strong>+ Tambah Slot</strong> di atas untuk membuat jadwal baru.
                </p>
            @else
                <p style="font-size:0.9375rem;color:#64748B;margin:0;">
                    Pilih lapangan terlebih dahulu untuk melihat slot.
                </p>
            @endif
        </div>
    @endif
</div>

{{-- ─── Modal: Tambah Slot (FR-B4, FR-B5) ───────────────────── --}}
@if($selectedLapangan)
<div id="add-slot-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;
            align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:12px;width:100%;max-width:460px;">
        <div style="padding:18px 22px;border-bottom:1px solid #F1F5F9;
                    display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin:0;">
                Tambah Slot Baru
            </h3>
            <button onclick="document.getElementById('add-slot-modal').style.display='none'"
                    style="background:none;border:none;cursor:pointer;color:#94A3B8;">
                <i data-lucide="x" style="width:18px;height:18px;"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('owner.schedules.store') }}">
        @csrf
        <input type="hidden" name="lapangan_id" value="{{ $selectedLapangan->id }}">
        <input type="hidden" name="tanggal" value="{{ $selectedTanggal }}">
        <div style="padding:22px;display:flex;flex-direction:column;gap:14px;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-input" required>
                </div>
            </div>

            {{-- Jenis slot (FR-B5) --}}
            <div>
                <label class="form-label">Jenis Slot</label>
                <div style="display:flex;gap:10px;">
                    <label style="flex:1;cursor:pointer;">
                        <input type="radio" name="tipe" value="biasa" checked
                               onchange="toggleOpenMatchFields(false)"
                               style="display:none;" id="radio-normal">
                        <div id="box-normal" style="border:2px solid #16A34A;border-radius:7px;padding:12px;
                                    background:#F0FDF4;text-align:center;">
                            <i data-lucide="calendar" style="width:18px;height:18px;color:#16A34A;margin-bottom:4px;"></i>
                            <div style="font-size:0.8125rem;font-weight:700;color:#166534;">Booking Biasa</div>
                        </div>
                    </label>
                    <label style="flex:1;cursor:pointer;">
                        <input type="radio" name="tipe" value="open_match"
                               onchange="toggleOpenMatchFields(true)"
                               style="display:none;" id="radio-om">
                        <div id="box-om" style="border:2px solid #E2E8F0;border-radius:7px;padding:12px;
                                   background:#fff;text-align:center;">
                            <i data-lucide="users" style="width:18px;height:18px;color:#64748B;margin-bottom:4px;"></i>
                            <div style="font-size:0.8125rem;font-weight:700;color:#1E293B;">Open Match</div>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <label class="form-label">Harga Total Lapangan (Rp)</label>
                <input type="number" name="harga" id="slot-price" class="form-input" placeholder="90000" min="0" required
                       oninput="calcPerSeat()">
            </div>

            {{-- Open Match fields (FR-B5) --}}
            <div id="om-fields" style="display:none;">
                <label class="form-label">Jumlah Maksimal Pemain (Berapa Orang)</label>
                <input type="number" name="kuota_total" id="slot-quota" class="form-input" placeholder="Misal: 4 (Bulu Tangkis) atau 10 (Futsal)" min="2"
                       oninput="calcPerSeat()">
                <div id="per-seat-label" style="font-size:0.8125rem;color:#EA580C;margin-top:6px;display:none;">
                    Harga per kursi: <strong id="per-seat-val">–</strong>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #F1F5F9;">
                <button type="button" onclick="document.getElementById('add-slot-modal').style.display='none'"
                        class="btn-outline btn-sm">Batal</button>
                <button type="submit" class="btn-brand btn-sm">
                    <i data-lucide="save" style="width:13px;height:13px;"></i>
                    Simpan Slot
                </button>
            </div>
        </div>
        </form>
    </div>
</div>
@endif

@push('scripts')
<script>
    function toggleOpenMatchFields(show) {
        const omFields = document.getElementById('om-fields');
        const boxNormal = document.getElementById('box-normal');
        const boxOM    = document.getElementById('box-om');
        omFields.style.display = show ? 'block' : 'none';

        if (show) {
            boxOM.style.border     = '2px solid #EA580C';
            boxOM.style.background = '#FFF7ED';
            boxNormal.style.border = '2px solid #E2E8F0';
            boxNormal.style.background = '#fff';
        } else {
            boxNormal.style.border = '2px solid #16A34A';
            boxNormal.style.background = '#F0FDF4';
            boxOM.style.border     = '2px solid #E2E8F0';
            boxOM.style.background = '#fff';
        }
    }

    /* Hitung harga per kursi otomatis (FR-E3) */
    function calcPerSeat() {
        const price = parseInt(document.getElementById('slot-price').value) || 0;
        const quota = parseInt(document.getElementById('slot-quota').value) || 0;
        const label = document.getElementById('per-seat-label');
        const val   = document.getElementById('per-seat-val');

        if (price > 0 && quota > 0) {
            label.style.display = 'block';
            val.textContent = 'Rp ' + Math.ceil(price / quota).toLocaleString('id-ID');
        } else {
            label.style.display = 'none';
        }
    }
</script>
@endpush

@endsection
