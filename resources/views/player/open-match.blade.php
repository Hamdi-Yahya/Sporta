@extends('layouts.dashboard')

@section('title', 'Cari Partner — Open Match')
@section('page_title', 'Cari Partner / Open Match')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

{{-- ─── Filter bar — form GET ke route open-match ────────── --}}
<form method="GET" action="{{ route('player.open-match') }}" id="open-match-filter">
<div class="card" style="padding:14px 20px;margin-bottom:20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
    <i data-lucide="filter" style="width:16px;height:16px;color:#64748B;flex-shrink:0;"></i>
    <span style="font-size:0.8125rem;font-weight:600;color:#1E293B;flex-shrink:0;">Filter:</span>

    {{-- Filter: Cabang Olahraga --}}
    <select name="cabor_id" class="form-input" style="width:auto;padding:7px 12px;font-size:0.8125rem;"
            onchange="this.form.submit()">
        <option value="">Semua Olahraga</option>
        @foreach($caborList as $cabor)
            <option value="{{ $cabor->id }}" {{ request('cabor_id') == $cabor->id ? 'selected' : '' }}>
                {{ $cabor->nama_cabor }}
            </option>
        @endforeach
    </select>

    {{-- Sort --}}
    <select name="sort" class="form-input" style="width:auto;padding:7px 12px;font-size:0.8125rem;"
            onchange="this.form.submit()">
        <option value="tanggal"   {{ request('sort','tanggal') === 'tanggal'   ? 'selected' : '' }}>Tanggal Terdekat</option>
        <option value="kuota"     {{ request('sort') === 'kuota'     ? 'selected' : '' }}>Slot Hampir Penuh</option>
        <option value="harga_asc" {{ request('sort') === 'harga_asc' ? 'selected' : '' }}>Harga Terendah</option>
    </select>

    {{-- Sembunyikan penuh --}}
    <label style="display:flex;align-items:center;gap:6px;font-size:0.8125rem;cursor:pointer;white-space:nowrap;">
        <input type="checkbox" name="sembunyikan_penuh" value="1"
               style="accent-color:#16A34A;"
               {{ request('sembunyikan_penuh') ? 'checked' : '' }}
               onchange="this.form.submit()">
        Sembunyikan yang Penuh
    </label>

    <div style="margin-left:auto;font-size:0.8125rem;color:#64748B;white-space:nowrap;">
        <strong style="color:#1E293B;">{{ $slots->total() }}</strong> slot tersedia
    </div>

    @if(request()->hasAny(['cabor_id','sort','sembunyikan_penuh']))
    <a href="{{ route('player.open-match') }}" style="font-size:0.8125rem;color:#DC2626;text-decoration:none;white-space:nowrap;">
        <i data-lucide="x" style="width:12px;height:12px;display:inline;"></i> Reset
    </a>
    @endif
</div>
</form>

{{-- ─── Open Match Cards — data real dari database ──────── --}}
<div style="display:flex;flex-direction:column;gap:14px;">
    @forelse($slots as $slot)
    @php
        $lapangan   = $slot->lapangan;
        $cabor      = $lapangan->cabangOlahraga;
        $bookedKursi = \App\Models\Booking::where('slot_id', $slot->id)
            ->whereNotIn('status', ['expired','ditolak'])
            ->sum('jumlah_kursi');
        $kuotaTotal  = $slot->kuota_total ?? 10;
        $pct         = $kuotaTotal > 0 ? round($bookedKursi / $kuotaTotal * 100) : 0;
        $isFull      = $bookedKursi >= $kuotaTotal;
        $remaining   = max(0, $kuotaTotal - $bookedKursi);
        $hargaPerKursi = $kuotaTotal > 0 ? round($slot->harga / $kuotaTotal) : $slot->harga;
        $tglStr      = \Carbon\Carbon::parse($slot->tanggal)->translatedFormat('l, d M');
        $waktuStr    = \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i') . '–' . \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i');
    @endphp
    <div class="card" style="padding:20px;{{ $isFull ? 'opacity:0.65;' : '' }}">
        <div style="display:grid;grid-template-columns:1fr auto;gap:20px;align-items:center;">

            {{-- Left: info --}}
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;flex-wrap:wrap;">
                    <span style="background:#FFF7ED;border:1px solid #FED7AA;border-radius:4px;
                                 font-size:0.75rem;font-weight:700;color:#EA580C;padding:2px 9px;">
                        {{ $cabor->nama_cabor }}
                    </span>
                    @if($isFull)
                        <span class="badge badge-danger">Penuh</span>
                    @else
                        <span class="badge badge-success">Tersedia</span>
                    @endif
                </div>

                <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;
                           color:#1E293B;margin:0 0 6px;">{{ $lapangan->nama }}</h3>

                <div style="display:flex;gap:16px;font-size:0.8125rem;color:#64748B;flex-wrap:wrap;margin-bottom:14px;">
                    <span style="display:flex;align-items:center;gap:4px;">
                        <i data-lucide="map-pin" style="width:13px;height:13px;"></i>{{ $lapangan->lokasi }}
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;">
                        <i data-lucide="calendar" style="width:13px;height:13px;"></i>{{ $tglStr }}
                    </span>
                    <span style="display:flex;align-items:center;gap:4px;">
                        <i data-lucide="clock" style="width:13px;height:13px;"></i>{{ $waktuStr }}
                    </span>
                </div>

                {{-- Progress kuota --}}
                <div style="display:flex;align-items:center;gap:10px;">
                    <div class="progress-bar" style="flex:1;">
                        <div class="progress-bar-fill" style="width:{{ $pct }}%;
                             background-color:{{ $pct >= 100 ? '#DC2626' : ($pct >= 75 ? '#F59E0B' : '#EA580C') }};"></div>
                    </div>
                    <span style="font-size:0.8125rem;font-weight:700;color:#1E293B;white-space:nowrap;">
                        {{ $bookedKursi }}/{{ $kuotaTotal }} Pemain
                    </span>
                </div>

                @if(!$isFull)
                <p style="font-size:0.75rem;color:#EA580C;font-weight:600;margin:5px 0 0;">
                    Masih ada {{ $remaining }} kursi tersisa
                </p>
                @endif
            </div>

            {{-- Right: price & CTA --}}
            <div style="text-align:right;min-width:140px;">
                <div style="font-size:0.75rem;color:#94A3B8;margin-bottom:2px;">Harga per orang</div>
                <div style="font-family:'Poppins',sans-serif;font-size:1.25rem;font-weight:800;color:#166534;margin-bottom:4px;">
                    Rp {{ number_format($hargaPerKursi, 0, ',', '.') }}
                </div>
                <div style="font-size:0.75rem;color:#94A3B8;margin-bottom:14px;">
                    dari total Rp {{ number_format($slot->harga, 0, ',', '.') }}
                </div>

                @if(!$isFull)
                <form method="POST" action="{{ route('player.open-match.book') }}">
                    @csrf
                    <input type="hidden" name="slot_id" value="{{ $slot->id }}">
                    <button type="submit" class="btn-brand" style="width:100%;">
                        <i data-lucide="user-plus" style="width:15px;height:15px;"></i>
                        Bergabung
                    </button>
                </form>
                @else
                <button class="btn-brand" disabled
                        style="opacity:0.4;cursor:not-allowed;background:#64748B;width:100%;">
                    Slot Penuh
                </button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div style="text-align:center;padding:48px 24px;background:#F8FAFC;border-radius:10px;border:1px dashed #E2E8F0;">
        <i data-lucide="users-round" style="width:40px;height:40px;color:#94A3B8;margin-bottom:12px;opacity:0.5;"></i>
        <p style="font-size:0.9375rem;color:#64748B;margin:0;">Tidak ada slot Open Match yang tersedia saat ini.</p>
        <a href="{{ route('player.open-match') }}" style="color:#16A34A;font-weight:600;font-size:0.875rem;text-decoration:none;margin-top:8px;display:inline-block;">Hapus filter</a>
    </div>
    @endforelse
</div>

<div style="margin-top:20px;">
    {{ $slots->appends(request()->query())->links() }}
</div>

{{-- Empty state hint --}}
<div style="margin-top:16px;text-align:center;padding:12px;">
    <p style="font-size:0.875rem;color:#94A3B8;">
        Tidak ada slot Open Match yang sesuai?
        <a href="{{ route('player.booking') }}" style="color:#16A34A;font-weight:600;text-decoration:none;">Cari & buat booking biasa →</a>
    </p>
</div>

@endsection
