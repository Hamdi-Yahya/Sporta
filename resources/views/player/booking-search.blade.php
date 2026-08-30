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
// $lapangans and $caborList are provided by BookingSearchController
@endphp

<style>
/* ── Responsive Booking Search ── */
@media (max-width: 900px) {
    .booking-layout { grid-template-columns: 1fr !important; }
    .booking-filter-panel { display: none; }
    .booking-filter-panel.open { display: block !important; }
    .booking-filter-toggle { display: flex !important; }
}
@media (min-width: 901px) {
    .booking-filter-toggle { display: none !important; }
}
@media (max-width: 600px) {
    .booking-card-grid { grid-template-columns: 1fr !important; }
    .booking-searchbar { flex-wrap: wrap; }
}
@media (max-width: 900px) and (min-width: 601px) {
    .booking-card-grid { grid-template-columns: repeat(2, 1fr) !important; }
}
</style>

{{-- Toggle Filter (mobile only) --}}
<button class="btn-outline booking-filter-toggle" style="display:none;margin-bottom:14px;width:100%;justify-content:center;"
        onclick="document.querySelector('.booking-filter-panel').classList.toggle('open')">
    <i data-lucide="sliders-horizontal" style="width:15px;height:15px;"></i>
    Filter Lapangan
</button>

{{-- ─── Semua filter dalam 1 form yang wrap seluruh layout ──── --}}
<form method="GET" action="{{ route('player.booking.search') }}" id="filter-form">

<div style="display:grid;grid-template-columns:240px 1fr;gap:24px;align-items:flex-start;" class="booking-layout">

    {{-- ─── Panel Filter Kiri ──────────────────────────────────── --}}
    <aside class="booking-filter-panel">
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
                    @foreach($caborList as $cabor)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        {{-- Nama parameter: cabor_id[] — sesuai dengan controller --}}
                        <input type="checkbox" name="cabor_id[]" value="{{ $cabor->id }}"
                               style="width:15px;height:15px;accent-color:#16A34A;"
                               {{ in_array($cabor->id, (array) request('cabor_id', [])) ? 'checked' : '' }}>
                        <span style="font-size:0.8125rem;color:#475569;">{{ $cabor->nama_cabor }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <div style="height:1px;background:#F1F5F9;margin-bottom:18px;"></div>

            {{-- Lokasi --}}
            <div style="margin-bottom:18px;">
                <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0 0 8px;">Lokasi / Kecamatan</p>
                {{-- Nama parameter: lokasi — sesuai dengan controller --}}
                <input type="text" name="lokasi" class="form-input"
                       placeholder="Cari lokasi..."
                       style="font-size:0.8125rem;"
                       value="{{ request('lokasi') }}">
            </div>

            <div style="height:1px;background:#F1F5F9;margin-bottom:18px;"></div>

            {{-- Harga --}}
            <div style="margin-bottom:18px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;">
                    <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0;">Harga Maks / Jam</p>
                    <span style="font-size:0.8125rem;color:#16A34A;font-weight:600;" id="price-label">
                        {{ request('harga_max') ? '≤ Rp '.number_format(request('harga_max')/1000).'rb' : '≤ Rp 200rb' }}
                    </span>
                </div>
                {{-- Nama parameter: harga_max — sesuai dengan controller --}}
                <input type="range" name="harga_max" min="0" max="200000" step="10000"
                       value="{{ request('harga_max', 200000) }}"
                       style="width:100%;accent-color:#16A34A;"
                       oninput="document.getElementById('price-label').textContent='≤ Rp '+(this.value/1000)+'rb'">
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#94A3B8;margin-top:4px;">
                    <span>Rp 0</span><span>Rp 200rb</span>
                </div>
            </div>

            <div style="height:1px;background:#F1F5F9;margin-bottom:18px;"></div>

            {{-- Tipe Lapangan --}}
            <div style="margin-bottom:20px;">
                <p style="font-size:0.8125rem;font-weight:700;color:#1E293B;margin:0 0 10px;">Tipe Lapangan</p>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach(['Indoor','Outdoor'] as $t)
                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                        {{-- Nama parameter: type[] --}}
                        <input type="checkbox" name="type[]" value="{{ strtolower($t) }}"
                               style="width:15px;height:15px;accent-color:#16A34A;"
                               {{ in_array(strtolower($t), (array) request('type', [])) ? 'checked' : '' }}>
                        <span style="font-size:0.8125rem;color:#475569;">{{ $t }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Tombol submit filter --}}
            <button type="submit" class="btn-brand" style="width:100%;">
                <i data-lucide="search" style="width:15px;height:15px;"></i>
                Terapkan Filter
            </button>
            {{-- Reset: ke halaman index tanpa parameter --}}
            <a href="{{ route('player.booking') }}" class="btn-outline" style="width:100%;margin-top:8px;font-size:0.8125rem;display:flex;align-items:center;justify-content:center;gap:6px;text-decoration:none;">
                <i data-lucide="x" style="width:13px;height:13px;"></i>
                Reset Filter
            </a>
        </div>
    </aside>

    {{-- ─── Grid Hasil Lapangan ────────────────────────────────── --}}
    <div>
        {{-- Search bar nama + sort --}}
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;" class="booking-searchbar">
            <div style="flex:1;min-width:200px;position:relative;">
                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                    <i data-lucide="search" style="width:16px;height:16px;"></i>
                </span>
                {{-- Nama parameter: nama — dicari di controller lewat nama lapangan --}}
                <input type="text" name="nama" class="form-input" style="padding-left:38px;"
                       placeholder="Cari nama lapangan..."
                       value="{{ request('nama') }}">
            </div>
            <select name="sort" class="form-input" style="width:auto;padding:10px 14px;cursor:pointer;flex-shrink:0;"
                    onchange="this.form.submit()">
                <option value="latest"  {{ request('sort','latest')  === 'latest'  ? 'selected' : '' }}>Terbaru</option>
                <option value="rating"  {{ request('sort') === 'rating'  ? 'selected' : '' }}>Rating Tertinggi</option>
                <option value="harga_asc"  {{ request('sort') === 'harga_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                <option value="harga_desc" {{ request('sort') === 'harga_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
            </select>
        </div>

        {{-- Result count --}}
        <p style="font-size:0.875rem;color:#64748B;margin-bottom:16px;">
            Menampilkan <strong style="color:#1E293B;">{{ $lapangans->total() }}</strong> lapangan tersedia
            @if(request()->hasAny(['cabor_id','lokasi','harga_max','type','nama']))
            — <a href="{{ route('player.booking') }}" style="color:#DC2626;font-size:0.8125rem;text-decoration:none;">hapus filter</a>
            @endif
        </p>

        {{-- Grid lapangan: 3 kolom di desktop, 2 di tablet, 1 di mobile --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;" class="booking-card-grid">
            @forelse($lapangans as $lapangan)
                <x-field-card
                    :name="$lapangan->nama"
                    :sport="$lapangan->cabangOlahraga->nama_cabor"
                    :type="str_contains(strtolower($lapangan->fasilitas), 'indoor') ? 'Indoor' : 'Outdoor'"
                    :location="$lapangan->lokasi"
                    :price="number_format($lapangan->slots()->min('harga') ?? 50000, 0, ',', '.')"
                    :rating="$lapangan->rating_rata2"
                    :reviewCount="$lapangan->jumlah_ulasan"
                    :href="'/player/booking/lapangan/'.$lapangan->id"
                />
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:48px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;">
                <i data-lucide="search-x" style="width:40px;height:40px;color:#94A3B8;margin-bottom:12px;opacity:0.5;"></i>
                <p style="font-size:0.9375rem;color:#64748B;margin:0;">Tidak ada lapangan yang cocok dengan filter ini.</p>
                <a href="{{ route('player.booking') }}" style="color:#16A34A;font-weight:600;font-size:0.875rem;text-decoration:none;margin-top:8px;display:inline-block;">Reset Filter</a>
            </div>
            @endforelse
        </div>

        <div style="margin-top:20px;">
            {{ $lapangans->appends(request()->query())->links() }}
        </div>
    </div>
</div>

</form>

@endsection
