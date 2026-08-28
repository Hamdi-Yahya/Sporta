@extends('layouts.dashboard')

@section('title', 'Kelola Lapangan')
@section('page_title', 'Kelola Lapangan Saya')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

@php
/* Dummy data lapangan owner (FR-B1–B3) */
$fields = [
    ['id'=>1,'name'=>'Futsal Planet Pekalongan','sport'=>'Futsal','type'=>'Indoor',
     'location'=>'Jl. Dr. Cipto No. 12, Pekalongan Barat','price'=>'80.000–100.000',
     'rating'=>4.8,'reviewCount'=>41,'status'=>'approved',
     'facilities'=>['AC','Parkir','Kantin','Wifi']],
];
$pendingFields = [
    ['id'=>2,'name'=>'Futsal Planet 2','sport'=>'Futsal','type'=>'Indoor',
     'location'=>'Jl. Sriwijaya No. 5, Pekalongan Timur','submitted'=>'26 Agt 2026','status'=>'pending'],
];
@endphp

{{-- ─── Header actions ─────────────────────────────────────── --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
    <p style="font-size:0.875rem;color:#64748B;margin:0;">
        Kelola semua lapangan yang kamu daftarkan di SPORTA.
    </p>
    <button onclick="document.getElementById('add-field-modal').style.display='flex'"
            class="btn-brand">
        <i data-lucide="plus" style="width:15px;height:15px;"></i>
        Daftarkan Lapangan Baru
    </button>
</div>

{{-- ─── Lapangan yang sudah approved ──────────────────────── --}}
@if(count($fields))
<h3 class="section-title" style="font-size:0.9375rem;margin-bottom:14px;">Lapangan Aktif</h3>
<div style="display:flex;flex-direction:column;gap:14px;margin-bottom:24px;">
    @foreach($fields as $f)
    <div class="card" style="padding:20px;">
        <div style="display:flex;align-items:flex-start;gap:18px;">
            {{-- Foto placeholder --}}
            <div style="width:120px;height:90px;border-radius:8px;background:linear-gradient(135deg,#1E293B,#334155);
                        display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i data-lucide="image" style="width:24px;height:24px;color:#64748B;"></i>
            </div>

            <div style="flex:1;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px;">
                    <div>
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;
                                       color:#1E293B;margin:0;">{{ $f['name'] }}</h3>
                            <span class="badge badge-success">
                                <i data-lucide="check-circle" style="width:10px;height:10px;"></i>
                                Disetujui Admin
                            </span>
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;display:flex;align-items:center;gap:5px;">
                            <i data-lucide="map-pin" style="width:12px;height:12px;"></i>
                            {{ $f['location'] }}
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#166534;">
                            Rp {{ $f['price'] }}/jam
                        </div>
                        <div style="font-size:0.8125rem;color:#64748B;">
                            {{ $f['sport'] }} • {{ $f['type'] }}
                        </div>
                    </div>
                </div>

                <div style="display:flex;gap:14px;font-size:0.8125rem;color:#64748B;margin-bottom:12px;">
                    <span style="display:flex;align-items:center;gap:4px;">
                        @for($i=1;$i<=5;$i++)
                            <i data-lucide="star" style="width:12px;height:12px;color:{{ $i<=$f['rating']?'#F59E0B':'#CBD5E1' }};fill:{{ $i<=$f['rating']?'#F59E0B':'transparent' }};"></i>
                        @endfor
                        <strong>{{ $f['rating'] }}</strong> ({{ $f['reviewCount'] }} ulasan)
                    </span>
                </div>

                {{-- Facilities --}}
                <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:14px;">
                    @foreach($f['facilities'] as $fac)
                    <span style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:4px;
                                 padding:2px 8px;font-size:0.75rem;color:#166534;font-weight:500;">
                        {{ $fac }}
                    </span>
                    @endforeach
                </div>

                <div style="display:flex;gap:8px;">
                    <a href="/owner/schedules" class="btn-brand btn-sm">
                        <i data-lucide="calendar-days" style="width:13px;height:13px;"></i>
                        Kelola Jadwal
                    </a>
                    <button class="btn-outline btn-sm">
                        <i data-lucide="edit-2" style="width:13px;height:13px;"></i>
                        Edit Lapangan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ─── Lapangan pending approval (FR-B2, FR-B3) ──────────── --}}
@if(count($pendingFields))
<h3 class="section-title" style="font-size:0.9375rem;margin-bottom:14px;">Menunggu Persetujuan Admin</h3>
<div style="display:flex;flex-direction:column;gap:12px;">
    @foreach($pendingFields as $pf)
    <div class="card" style="padding:16px 18px;border-left:3px solid #F59E0B;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;margin-bottom:4px;">
                    {{ $pf['name'] }}
                    <span class="badge badge-warning" style="margin-left:8px;">
                        <i data-lucide="clock" style="width:10px;height:10px;"></i>
                        Menunggu Review Admin
                    </span>
                </div>
                <div style="font-size:0.8125rem;color:#64748B;">
                    {{ $pf['sport'] }} • {{ $pf['type'] }} • {{ $pf['location'] }}
                </div>
                <div style="font-size:0.75rem;color:#94A3B8;margin-top:4px;">
                    Diajukan: {{ $pf['submitted'] }}
                </div>
            </div>
            <div style="text-align:right;">
                <p style="font-size:0.8125rem;color:#92400E;margin:0 0 8px;">
                    Lapangan belum tayang ke publik<br>hingga Admin menyetujui.
                </p>
                <button class="btn-outline btn-sm">
                    <i data-lucide="edit-2" style="width:13px;height:13px;"></i>
                    Edit Data
                </button>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

{{-- ─── Modal: Daftarkan Lapangan Baru (FR-B1) ──────────────── --}}
<div id="add-field-modal"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:200;
            align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:12px;width:100%;max-width:600px;
                max-height:90vh;overflow-y:auto;">
        <div style="padding:20px 24px;border-bottom:1px solid #F1F5F9;
                    display:flex;align-items:center;justify-content:space-between;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#1E293B;margin:0;">
                Daftarkan Lapangan Baru
            </h3>
            <button onclick="document.getElementById('add-field-modal').style.display='none'"
                    style="background:none;border:none;cursor:pointer;color:#94A3B8;">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>
        <div style="padding:24px;display:flex;flex-direction:column;gap:16px;">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                <div style="grid-column:1/-1;">
                    <label class="form-label">Nama Lapangan</label>
                    <input type="text" class="form-input" placeholder="Nama GOR / lapangan kamu">
                </div>
                <div>
                    <label class="form-label">Jenis Olahraga</label>
                    <select class="form-input">
                        @foreach(['Futsal','Bulu Tangkis','Basket','Tenis','Padel','Mini Soccer'] as $s)
                        <option>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Tipe Lapangan</label>
                    <select class="form-input">
                        <option>Indoor</option>
                        <option>Outdoor</option>
                    </select>
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label">Alamat Lengkap</label>
                    <input type="text" class="form-input" placeholder="Jl. ... No. ..., Kelurahan, Kecamatan">
                </div>
                <div>
                    <label class="form-label">Harga per Jam (Rp)</label>
                    <input type="number" class="form-input" placeholder="80000">
                </div>
                <div>
                    <label class="form-label">No. HP / WA Kontak</label>
                    <input type="tel" class="form-input" placeholder="08xxxxxxxxxx">
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label">Deskripsi Lapangan</label>
                    <textarea class="form-input" style="resize:none;min-height:80px;" placeholder="Fasilitas, kapasitas, kondisi, dll."></textarea>
                </div>
                <div style="grid-column:1/-1;">
                    <label class="form-label">Upload Foto Lapangan</label>
                    <div style="border:2px dashed #CBD5E1;border-radius:8px;padding:24px;text-align:center;cursor:pointer;">
                        <i data-lucide="upload-cloud" style="width:28px;height:28px;color:#94A3B8;margin-bottom:6px;"></i>
                        <div style="font-size:0.8125rem;color:#64748B;">Klik untuk upload foto</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">JPG, PNG — Maks. 5 MB per foto</div>
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:8px;border-top:1px solid #F1F5F9;">
                <button onclick="document.getElementById('add-field-modal').style.display='none'"
                        class="btn-outline">Batal</button>
                <button class="btn-brand">
                    <i data-lucide="send" style="width:15px;height:15px;"></i>
                    Submit untuk Review Admin
                </button>
            </div>
        </div>
    </div>
</div>

@endsection
