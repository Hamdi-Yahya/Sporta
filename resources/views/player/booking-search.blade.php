@extends('layouts.dashboard')

@section('title', 'Booking Lapangan')
@section('page_title', 'Cari & Booking Lapangan')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
/* Dummy data lapangan */
$fields = [
    ['id'=>1,'name'=>'Futsal Planet Pekalongan',  'sport'=>'Futsal',      'type'=>'Indoor',  'location'=>'Pekalongan Barat',  'price'=>'90.000',  'rating'=>4.8,'reviewCount'=>41],
    ['id'=>2,'name'=>'GOR Bulu Tangkis Arinda',   'sport'=>'Bulu Tangkis','type'=>'Indoor',  'location'=>'Pekalongan Timur', 'price'=>'55.000',  'rating'=>4.6,'reviewCount'=>28],
    ['id'=>3,'name'=>'Lapangan Basket Pemuda',    'sport'=>'Basket',      'type'=>'Outdoor', 'location'=>'Kota Pekalongan',  'price'=>'75.000',  'rating'=>4.5,'reviewCount'=>17],
    ['id'=>4,'name'=>'Tenis Indoor Batik City',   'sport'=>'Tenis',       'type'=>'Indoor',  'location'=>'Pekalongan Selatan','price'=>'110.000', 'rating'=>4.7,'reviewCount'=>33],
    ['id'=>5,'name'=>'Padel Court Pekalongan',    'sport'=>'Padel',       'type'=>'Indoor',  'location'=>'Pekalongan Utara', 'price'=>'130.000', 'rating'=>4.9,'reviewCount'=>12],
    ['id'=>6,'name'=>'Mini Soccer Arena',         'sport'=>'Mini Soccer', 'type'=>'Outdoor', 'location'=>'Pekalongan Barat', 'price'=>'85.000',  'rating'=>4.4,'reviewCount'=>22],
    ['id'=>7,'name'=>'Futsal Kings Center',       'sport'=>'Futsal',      'type'=>'Indoor',  'location'=>'Pekalongan Timur', 'price'=>'80.000',  'rating'=>4.3,'reviewCount'=>15],
    ['id'=>8,'name'=>'GOR Basket Harapan',        'sport'=>'Basket',      'type'=>'Indoor',  'location'=>'Pekalongan Barat', 'price'=>'70.000',  'rating'=>4.6,'reviewCount'=>20],
    ['id'=>9,'name'=>'Lapangan Padel Premiere',   'sport'=>'Padel',       'type'=>'Indoor',  'location'=>'Kota Pekalongan',  'price'=>'140.000', 'rating'=>4.8,'reviewCount'=>9],
];

$sports = ['Futsal','Bulu Tangkis','Basket','Tenis','Padel','Mini Soccer'];
@endphp

<div style="display:grid;grid-template-columns:240px 1fr;gap:24px;align-items:flex-start;">

    {{-- ─── Panel Filter Kiri (§3.6 §3.7) ────────────────────── --}}
    <aside>
        <div class="card" style="padding:20px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 18px;display:flex;align-items:center;gap:7px;">
                <i data-lucide="sliders-horizontal" style="width:16px;height:16px;color:#16A34A;"></i>
                Filter
            </h3>

            {{-- Jenis Olahraga --}}
            <div style="margin-bottom:20px;">
                <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0 0 10px;">Jenis Olahraga</p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach($sports as $sport)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="sport[]" value="{{ strtolower($sport) }}"
                               style="width:15px;height:15px;accent-color:#16A34A;">
                        <span style="font-size:0.8125rem;color:#475569;">{{ $sport }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="height:1px;background:#F1F5F9;margin-bottom:18px;"></div>

            {{-- Lokasi --}}
            <div style="margin-bottom:18px;">
                <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0 0 8px;">Lokasi / Kecamatan</p>
                <input type="text" class="form-input" placeholder="Cari lokasi..." style="font-size:0.8125rem;">
            </div>

            <div style="height:1px;background:#F1F5F9;margin-bottom:18px;"></div>

            {{-- Harga --}}
            <div style="margin-bottom:18px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0;">Harga / Jam</p>
                    <span style="font-size:0.8125rem;color:#16A34A;font-weight:600;" id="price-label">≤ Rp 200rb</span>
                </div>
                <input type="range" min="0" max="200000" step="10000" value="200000"
                       style="width:100%;accent-color:#16A34A;"
                       oninput="document.getElementById('price-label').textContent='≤ Rp '+(this.value/1000)+'rb'">
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#94A3B8;margin-top:4px;">
                    <span>Rp 0</span><span>Rp 200rb</span>
                </div>
            </div>

            <div style="height:1px;background:#F1F5F9;margin-bottom:18px;"></div>

            {{-- Tipe --}}
            <div style="margin-bottom:20px;">
                <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0 0 10px;">Tipe Lapangan</p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach(['Indoor','Outdoor'] as $t)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        <input type="checkbox" name="type[]" value="{{ strtolower($t) }}"
                               style="width:15px;height:15px;accent-color:#16A34A;">
                        <span style="font-size:0.8125rem;color:#475569;">{{ $t }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <button class="btn-brand" style="width:100%;">
                <i data-lucide="search" style="width:15px;height:15px;"></i>
                Terapkan Filter
            </button>
            <button class="btn-outline" style="width:100%;margin-top:8px;font-size:0.8125rem;">
                Reset Filter
            </button>
        </div>
    </aside>

    {{-- ─── Grid Hasil Lapangan ────────────────────────────────── --}}
    <div>
        {{-- Search bar + sort --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;">
            <div style="flex:1;position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                    <i data-lucide="search" style="width:16px;height:16px;"></i>
                </span>
                <input type="text" class="form-input" style="padding-left:38px;"
                       placeholder="Cari nama lapangan...">
            </div>
            <select class="form-input" style="width:auto;padding:10px 14px;cursor:pointer;">
                <option>Paling Relevan</option>
                <option>Rating Tertinggi</option>
                <option>Harga Terendah</option>
                <option>Harga Tertinggi</option>
            </select>
        </div>

        {{-- Result count --}}
        <p style="font-size:0.875rem;color:#64748B;margin-bottom:16px;">
            Menampilkan <strong style="color:#1E293B;">{{ count($fields) }}</strong> lapangan tersedia
        </p>

        {{-- Grid 3 kolom → responsif (§3.6) --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            @foreach($fields as $field)
                <x-field-card
                    :name="$field['name']"
                    :sport="$field['sport']"
                    :type="$field['type']"
                    :location="$field['location']"
                    :price="$field['price']"
                    :rating="$field['rating']"
                    :reviewCount="$field['reviewCount']"
                    :href="'/player/booking/'.$field['id']"
                />
            @endforeach
        </div>
    </div>
</div>

<style>
@media (max-width:1200px) {
    div[style*="grid-template-columns:240px 1fr"] { grid-template-columns: 1fr !important; }
    div[style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns: repeat(2,1fr) !important; }
}
</style>

@endsection
