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
/* Dummy slots (FR-B4, FR-B5, FR-B6) */
$slots = [
    ['id'=>1,'time'=>'07:00–08:00','type'=>'normal',     'price'=>'80.000',  'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>2,'time'=>'08:00–09:00','type'=>'normal',     'price'=>'80.000',  'status'=>'booked',     'date'=>'2026-08-28','bookedBy'=>'Bima S.'],
    ['id'=>3,'time'=>'09:00–10:00','type'=>'normal',     'price'=>'80.000',  'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>4,'time'=>'10:00–11:00','type'=>'open_match', 'price'=>'90.000',  'status'=>'available',  'date'=>'2026-08-28','quota_total'=>10,'quota_filled'=>5],
    ['id'=>5,'time'=>'11:00–12:00','type'=>'normal',     'price'=>'80.000',  'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>6,'time'=>'12:00–13:00','type'=>'normal',     'price'=>'90.000',  'status'=>'booked',     'date'=>'2026-08-28','bookedBy'=>'Ahmad F.'],
    ['id'=>7,'time'=>'13:00–14:00','type'=>'open_match', 'price'=>'90.000',  'status'=>'available',  'date'=>'2026-08-28','quota_total'=>10,'quota_filled'=>8],
    ['id'=>8,'time'=>'14:00–15:00','type'=>'normal',     'price'=>'90.000',  'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>9,'time'=>'15:00–16:00','type'=>'normal',     'price'=>'90.000',  'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>10,'time'=>'16:00–17:00','type'=>'normal',    'price'=>'90.000',  'status'=>'booked',     'date'=>'2026-08-28','bookedBy'=>'Dinda R.'],
    ['id'=>11,'time'=>'17:00–18:00','type'=>'open_match','price'=>'100.000', 'status'=>'available',  'date'=>'2026-08-28','quota_total'=>10,'quota_filled'=>2],
    ['id'=>12,'time'=>'18:00–19:00','type'=>'normal',    'price'=>'100.000', 'status'=>'booked',     'date'=>'2026-08-28','bookedBy'=>'Rizky M.'],
    ['id'=>13,'time'=>'19:00–20:00','type'=>'normal',    'price'=>'100.000', 'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>14,'time'=>'20:00–21:00','type'=>'normal',    'price'=>'100.000', 'status'=>'available',  'date'=>'2026-08-28'],
    ['id'=>15,'time'=>'21:00–22:00','type'=>'normal',    'price'=>'90.000',  'status'=>'available',  'date'=>'2026-08-28'],
];

$bookedCount = count(array_filter($slots, fn($s) => $s['status'] === 'booked'));
$availableCount = count($slots) - $bookedCount;
@endphp

{{-- ─── Date picker & field selector ─────────────────────── --}}
<div class="card" style="padding:16px 20px;margin-bottom:20px;
            display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
    <div>
        <label class="form-label" style="margin-bottom:4px;">Lapangan</label>
        <select class="form-input" style="width:auto;padding:8px 12px;font-size:0.875rem;">
            <option>Futsal Planet Pekalongan</option>
        </select>
    </div>
    <div>
        <label class="form-label" style="margin-bottom:4px;">Tanggal</label>
        <input type="date" class="form-input" value="2026-08-28" style="width:auto;">
    </div>
    <div style="margin-left:auto;display:flex;gap:10px;align-items:flex-end;">
        <button onclick="document.getElementById('add-slot-modal').style.display='flex'"
                class="btn-brand">
            <i data-lucide="plus" style="width:15px;height:15px;"></i>
            Tambah Slot
        </button>
    </div>
</div>

{{-- ─── Stats bar ───────────────────────────────────────────── --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
    @foreach([
        ['label'=>'Total Slot',          'val'=>count($slots), 'color'=>'#1E293B'],
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
    @foreach($slots as $slot)
    @php
        $bg = '#fff';
        $border = '#E2E8F0';
        $textColor = '#1E293B';

        if ($slot['type'] === 'open_match') {
            $bg = '#FFF7ED'; $border = '#FED7AA'; $textColor = '#9A3412';
        } elseif ($slot['status'] === 'booked') {
            $bg = '#DCFCE7'; $border = '#BBF7D0'; $textColor = '#166534';
        }
    @endphp
    <div style="border:1.5px solid {{ $border }};background:{{ $bg }};border-radius:8px;
                padding:10px 12px;position:relative;min-height:80px;">

        <div style="font-size:0.75rem;font-weight:700;color:{{ $textColor }};margin-bottom:3px;">
            {{ $slot['time'] }}
        </div>

        @if($slot['type'] === 'open_match')
            <div style="font-size:0.65rem;font-weight:700;color:#EA580C;background:#FED7AA;
                        border-radius:3px;padding:1px 5px;display:inline-block;margin-bottom:4px;">
                OPEN MATCH
            </div>
            <div style="font-size:0.7rem;color:#9A3412;">
                {{ $slot['quota_filled'] }}/{{ $slot['quota_total'] }} Pemain<br>
                Rp {{ number_format(intval(str_replace('.','',$slot['price'])) / $slot['quota_total']) }}/org
            </div>
            <div class="progress-bar" style="margin-top:5px;">
                <div class="progress-bar-fill" style="width:{{ ($slot['quota_filled']/$slot['quota_total'])*100 }}%;"></div>
            </div>
        @elseif($slot['status'] === 'booked')
            <div style="font-size:0.7rem;color:#166534;">
                Terpesan<br>{{ $slot['bookedBy'] ?? '' }}
            </div>
        @else
            <div style="font-size:0.7rem;color:#94A3B8;">Tersedia</div>
            <div style="font-size:0.7rem;color:#64748B;">Rp {{ $slot['price'] }}</div>
        @endif

        {{-- Action buttons (hanya untuk slot available FR-B6) --}}
        @if($slot['status'] === 'available')
        <div style="position:absolute;bottom:6px;right:6px;display:flex;gap:4px;">
            <button style="background:none;border:none;cursor:pointer;color:#94A3B8;padding:2px;"
                    title="Edit slot">
                <i data-lucide="edit-2" style="width:11px;height:11px;"></i>
            </button>
            <button style="background:none;border:none;cursor:pointer;color:#DC2626;padding:2px;"
                    title="Hapus slot">
                <i data-lucide="trash-2" style="width:11px;height:11px;"></i>
            </button>
        </div>
        @endif
    </div>
    @endforeach
</div>

{{-- ─── Modal: Tambah Slot (FR-B4, FR-B5) ───────────────────── --}}
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
        <div style="padding:22px;display:flex;flex-direction:column;gap:14px;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div>
                    <label class="form-label">Jam Mulai</label>
                    <input type="time" class="form-input" value="07:00">
                </div>
                <div>
                    <label class="form-label">Jam Selesai</label>
                    <input type="time" class="form-input" value="08:00">
                </div>
            </div>

            {{-- Jenis slot (FR-B5) --}}
            <div>
                <label class="form-label">Jenis Slot</label>
                <div style="display:flex;gap:10px;">
                    <label style="flex:1;cursor:pointer;">
                        <input type="radio" name="slot_type" value="normal" checked
                               onchange="toggleOpenMatchFields(false)"
                               style="display:none;" id="radio-normal">
                        <div id="box-normal" style="border:2px solid #16A34A;border-radius:7px;padding:12px;
                                    background:#F0FDF4;text-align:center;">
                            <i data-lucide="calendar" style="width:18px;height:18px;color:#16A34A;margin-bottom:4px;"></i>
                            <div style="font-size:0.8125rem;font-weight:700;color:#166534;">Booking Biasa</div>
                        </div>
                    </label>
                    <label style="flex:1;cursor:pointer;">
                        <input type="radio" name="slot_type" value="open_match"
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
                <input type="number" id="slot-price" class="form-input" placeholder="90000"
                       oninput="calcPerSeat()">
            </div>

            {{-- Open Match fields (FR-B5) --}}
            <div id="om-fields" style="display:none;">
                <label class="form-label">Kuota Pemain</label>
                <input type="number" id="slot-quota" class="form-input" placeholder="10" min="2"
                       oninput="calcPerSeat()">
                <div id="per-seat-label" style="font-size:0.8125rem;color:#EA580C;margin-top:6px;display:none;">
                    Harga per kursi: <strong id="per-seat-val">–</strong>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #F1F5F9;">
                <button onclick="document.getElementById('add-slot-modal').style.display='none'"
                        class="btn-outline btn-sm">Batal</button>
                <button class="btn-brand btn-sm">
                    <i data-lucide="save" style="width:13px;height:13px;"></i>
                    Simpan Slot
                </button>
            </div>
        </div>
    </div>
</div>

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
