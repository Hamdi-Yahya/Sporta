@extends('layouts.app')

@section('title', 'SPORTA')
@section('meta_description', 'SPORTA Platform Digital Booking Lapangan, Cari Partner & Komunitas Olahraga Kota Pekalongan. Satu platform untuk semua kebutuhan olahraga kamu.')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     NAVBAR
═══════════════════════════════════════════════════════════ --}}
@include('components.navbar')

@php
// $popularFields is passed from route controller
@endphp

{{-- ═══════════════════════════════════════════════════════════
     HERO SECTION — aksen hijau gelap §3.3
═══════════════════════════════════════════════════════════ --}}
<section class="hero-bg" style="min-height:100vh;display:flex;align-items:center;position:relative;background:#0B2415;">

    {{-- Background tekstur: garis diagonal halus --}}
    <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(
        -45deg,
        transparent,
        transparent 60px,
        rgba(22,163,74,0.03) 60px,
        rgba(22,163,74,0.03) 61px
    );pointer-events:none;"></div>

    {{-- Cahaya aksen kiri --}}
    <div style="position:absolute;top:0;left:0;width:600px;height:600px;
                background:radial-gradient(circle at top left, rgba(22,163,74,0.12) 0%, transparent 65%);
                pointer-events:none;"></div>

    {{-- Lingkaran dekoratif --}}
    <div style="position:absolute;top:12%;right:6%;width:380px;height:380px;
                border:1px solid rgba(22,163,74,0.12);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;top:22%;right:16%;width:200px;height:200px;
                border:1px solid rgba(22,163,74,0.08);border-radius:50%;pointer-events:none;"></div>

    {{-- Wave bawah --}}
    <div style="position:absolute;bottom:-1px;left:0;right:0;height:56px;
                background:#F8FAFC;clip-path:ellipse(55% 100% at 50% 100%);"></div>

    <div style="max-width:1280px;margin:0 auto;padding:50px 24px 140px;width:100%;position:relative;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:72px;align-items:center;" class="hero-grid">

            {{-- Kiri: Copy --}}
            <div>
                {{-- Label lokasi --}}
                <div style="display:inline-flex;align-items:center;gap:8px;
                            background:rgba(22,163,74,0.12);border:1px solid rgba(22,163,74,0.3);
                            border-radius:4px;padding:5px 12px;margin-bottom:28px;">
                    <i data-lucide="map-pin" style="width:13px;height:13px;color:#4ADE80;"></i>
                    <span style="font-size:0.75rem;font-weight:600;color:#4ADE80;letter-spacing:0.08em;">KOTA PEKALONGAN</span>
                </div>

                {{-- Heading utama --}}
                <h1 style="font-family:'Poppins',sans-serif;font-size:clamp(2.25rem,5vw,3.5rem);
                           font-weight:800;color:#fff;line-height:1.1;margin:0 0 8px;">
                    Satu Platform.
                </h1>
                <h1 style="font-family:'Poppins',sans-serif;font-size:clamp(2.25rem,5vw,3.5rem);
                           font-weight:800;color:#4ADE80;line-height:1.1;margin:0 0 8px;">
                    Semua Lapangan.
                </h1>
                <h1 style="font-family:'Poppins',sans-serif;font-size:clamp(2.25rem,5vw,3.5rem);
                           font-weight:800;color:#fff;line-height:1.1;margin:0 0 28px;
                           opacity:0.55;">
                    Olahraga Kamu.
                </h1>

                <p style="font-size:1rem;color:#86EFAC;line-height:1.75;margin:0 0 40px;max-width:460px;">
                    Booking lapangan, cari partner main, dan gabung komunitas olahraga
                    semuanya dalam satu platform digital untuk pemuda Pekalongan.
                </p>

                {{-- Search bar --}}
                <form action="/player/booking" method="GET" class="hero-search-form"
                      style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);
                             border-radius:8px;padding:6px;display:flex;gap:6px;margin-bottom:36px;
                             max-width:500px;flex-wrap:wrap;">
                    <select name="sport" style="flex:1;min-width:120px;background:transparent;border:none;outline:none;
                                color:#fff;font-size:0.875rem;padding:8px 10px;cursor:pointer;
                                font-family:'Inter',sans-serif;">
                        <option value="" style="background:#1E293B;">Semua Olahraga</option>
                        <option value="futsal"       style="background:#1E293B;">Futsal</option>
                        <option value="bulutangkis"  style="background:#1E293B;">Bulu Tangkis</option>
                        <option value="basket"       style="background:#1E293B;">Basket</option>
                        <option value="tenis"        style="background:#1E293B;">Tenis</option>
                        <option value="padel"        style="background:#1E293B;">Padel</option>
                        <option value="minisoccer"   style="background:#1E293B;">Mini Soccer</option>
                    </select>
                    <div style="width:1px;background:rgba(255,255,255,0.1);margin:4px 0;"></div>
                    <input type="text" name="location" placeholder="Lokasi / Kecamatan"
                           style="flex:1.2;min-width:120px;background:transparent;border:none;outline:none;
                                  color:#fff;font-size:0.875rem;padding:8px 10px;
                                  font-family:'Inter',sans-serif;">
                    <button type="submit" class="btn-brand" style="border-radius:6px;white-space:nowrap;">
                        <i data-lucide="search" style="width:15px;height:15px;"></i>
                        Cari Lapangan
                    </button>
                </form>

                {{-- Stats dengan garis pemisah vertikal --}}
                <div style="display:flex;gap:0;border:1px solid rgba(255,255,255,0.1);border-radius:8px;
                            overflow:hidden;max-width:380px;">
                    @foreach([['42+','Lapangan'],['6','Cabor'],['1.200+','Pengguna']] as $stat)
                    <div style="flex:1;padding:14px 16px;text-align:center;
                                {{ !$loop->last ? 'border-right:1px solid rgba(255,255,255,0.1);' : '' }}">
                        <div style="font-family:'Poppins',sans-serif;font-size:1.375rem;font-weight:800;color:#fff;">
                            {{ $stat[0] }}
                        </div>
                        <div style="font-size:0.75rem;color:#6EE7B7;margin-top:2px;">{{ $stat[1] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Kanan: Kartu ilustrasi --}}
            <div class="hero-right" style="display:flex;flex-direction:column;gap:12px;position:relative;">

                {{-- Booking terkonfirmasi --}}
                @if($latestBooking && $latestBooking->slot)
                @php
                    $slot    = $latestBooking->slot;
                    $namaLap = optional($slot->lapangan)->nama ?? 'Lapangan';
                    $hari    = \Carbon\Carbon::parse($slot->tanggal)->translatedFormat('l, d M');
                    $jam     = \Carbon\Carbon::parse($slot->jam_mulai)->format('H:i')
                             . ' – '
                             . \Carbon\Carbon::parse($slot->jam_selesai)->format('H:i');
                    $harga   = 'Rp ' . number_format($latestBooking->total_harga, 0, ',', '.');
                @endphp
                <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);
                            border-radius:12px;padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:40px;height:40px;background:#16A34A;border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i data-lucide="calendar-check" style="width:19px;height:19px;color:#fff;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:0.9375rem;">Booking Dikonfirmasi</div>
                            <div style="font-size:0.8rem;color:#86EFAC;margin-top:2px;">{{ $namaLap }} · {{ $hari }}</div>
                        </div>
                        <span class="badge badge-success" style="flex-shrink:0;">✓ Terkonfirmasi</span>
                    </div>
                    <div style="display:flex;gap:16px;font-size:0.8125rem;color:#94A3B8;padding-top:12px;
                                border-top:1px solid rgba(255,255,255,0.06);">
                        <span style="display:flex;align-items:center;gap:5px;">
                            <i data-lucide="clock" style="width:13px;height:13px;"></i> {{ $jam }}
                        </span>
                        <span style="display:flex;align-items:center;gap:5px;">
                            <i data-lucide="wallet" style="width:13px;height:13px;"></i> {{ $harga }}
                        </span>
                    </div>
                </div>
                @else
                <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);
                            border-radius:12px;padding:20px;">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
                        <div style="width:40px;height:40px;background:#16A34A;border-radius:8px;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i data-lucide="calendar-check" style="width:19px;height:19px;color:#fff;"></i>
                        </div>
                        <div style="flex:1;">
                            <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:0.9375rem;">Booking Lapangan</div>
                            <div style="font-size:0.8rem;color:#86EFAC;margin-top:2px;">Pesan lapangan favoritmu sekarang</div>
                        </div>
                        <span class="badge badge-success" style="flex-shrink:0;">✓ Mudah</span>
                    </div>
                    <div style="font-size:0.8125rem;color:#94A3B8;padding-top:12px;
                                border-top:1px solid rgba(255,255,255,0.06);">
                        Konfirmasi instan &bull; Pembayaran aman &bull; Jadwal real-time
                    </div>
                </div>
                @endif

                {{-- Open Match --}}
                @if($latestOpenMatch)
                @php
                    $namaLapOM   = optional($latestOpenMatch->lapangan)->nama ?? 'Lapangan';
                    $terisi      = $latestOpenMatch->kuota_terisi ?? 0;
                    $total       = $latestOpenMatch->kuota_total  ?? 1;
                    $persen      = $total > 0 ? round(($terisi / $total) * 100) : 0;
                    $hargaPerKur = 'Rp ' . number_format($latestOpenMatch->hargaPerKursi(), 0, ',', '.');
                @endphp
                <div style="background:rgba(234,88,12,0.1);border:1px solid rgba(234,88,12,0.25);
                            border-radius:12px;padding:18px;">
                    <div style="font-size:0.7rem;font-weight:700;color:#FB923C;letter-spacing:0.08em;margin-bottom:10px;">OPEN MATCH &mdash; BUTUH PARTNER</div>
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:0.9375rem;margin-bottom:14px;">{{ $namaLapOM }}</div>
                    <div class="progress-bar" style="margin-bottom:8px;"><div class="progress-bar-fill" style="width:{{ $persen }}%;"></div></div>
                    <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:#94A3B8;">
                        <span>{{ $terisi }}/{{ $total }} Pemain</span>
                        <span style="color:#FB923C;font-weight:600;">{{ $hargaPerKur }}/orang</span>
                    </div>
                </div>
                @else
                <div style="background:rgba(234,88,12,0.1);border:1px solid rgba(234,88,12,0.25);
                            border-radius:12px;padding:18px;">
                    <div style="font-size:0.7rem;font-weight:700;color:#FB923C;letter-spacing:0.08em;margin-bottom:10px;">OPEN MATCH &mdash; CARI PARTNER</div>
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:0.9375rem;margin-bottom:10px;">Bagi biaya, tambah teman main</div>
                    <div style="font-size:0.8rem;color:#94A3B8;">Belum ada open match aktif saat ini &mdash; buka sesi barumu!</div>
                </div>
                @endif

                {{-- Komunitas card --}}
                <div style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.09);
                            border-radius:12px;padding:16px;display:flex;align-items:center;gap:14px;">
                    <div style="display:flex;">
                        @foreach(['F','A','R','D'] as $i => $letter)
                        <div style="width:30px;height:30px;border-radius:50%;background:hsl({{ $i * 40 + 120 }},55%,42%);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:0.6875rem;font-weight:700;color:#fff;
                                    border:2px solid rgba(11,36,21,0.8);{{ $i > 0 ? 'margin-left:-8px;' : '' }}">{{ $letter }}</div>
                        @endforeach
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:0.875rem;font-weight:600;color:#fff;">Komunitas Futsal</div>
                        <div style="font-size:0.75rem;color:#6EE7B7;margin-top:2px;">148 anggota aktif</div>
                    </div>
                    <a href="/register" class="btn-brand btn-sm" style="flex-shrink:0;">Gabung</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     LAPANGAN POPULER
═══════════════════════════════════════════════════════════ --}}
<section id="lapangan-populer" style="padding:80px 0;background:#F8FAFC;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">

        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:36px;">
            <div>
                <div style="font-size:0.8125rem;font-weight:600;color:#16A34A;letter-spacing:0.05em;margin-bottom:6px;">LAPANGAN TERPOPULER</div>
                <h2 class="section-title" style="font-size:1.75rem;">Lapangan Terbaik di Pekalongan</h2>
                <p style="color:#64748B;margin-top:6px;font-size:0.9375rem;">Dipilih berdasarkan rating dan jumlah ulasan pemain</p>
            </div>
            <a href="/player/booking" class="btn-outline">
                Lihat Semua Lapangan
                <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
            </a>
        </div>

        {{-- Grid Card 3 kolom (§3.6) --}}
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;" class="grid-responsive-3">
            @foreach($popularFields as $field)
                <x-field-card
                    :name="$field['name']"
                    :sport="$field['sport']"
                    :type="$field['type']"
                    :location="$field['location']"
                    :price="$field['price']"
                    :rating="$field['rating']"
                    :reviewCount="$field['reviewCount']"
                    :image="$field['image'] ?? null"
                    :href="$field['href']"
                />
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FITUR UNGGULAN
═══════════════════════════════════════════════════════════ --}}
<section id="fitur" style="padding:96px 0;background:#fff;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">

        <div style="margin-bottom:56px;">
            <div style="font-size:0.75rem;font-weight:700;color:#16A34A;letter-spacing:0.1em;margin-bottom:8px;">FITUR PLATFORM</div>
            <h2 class="section-title" style="font-size:1.875rem;margin:0 0 8px;">Semua yang Kamu Butuhkan</h2>
            <p style="color:#64748B;font-size:0.9375rem;line-height:1.65;max-width:480px;">
                Dari booking lapangan hingga cari teman main, SPORTA hadir sebagai teman olahraga digital kamu.
            </p>
        </div>

        @php
        $features = [
            ['icon'=>'calendar-check', 'title'=>'Booking Lapangan',         'color'=>'#16A34A', 'num'=>'01',
             'desc'=>'Pesan lapangan favoritmu dalam hitungan detik. Cek jadwal real-time, bandingkan harga, dan konfirmasi langsung.'],
            ['icon'=>'users',          'title'=>'Open Match',                'color'=>'#EA580C', 'num'=>'02',
             'desc'=>'Kurang pemain? Buka slot Open Match dan bagi biaya sewa secara otomatis. Sistem yang mengatur semua kalkulasinya.'],
            ['icon'=>'message-circle', 'title'=>'Komunitas Olahraga',        'color'=>'#0891B2', 'num'=>'03',
             'desc'=>'Bergabung di ruang chat khusus cabang olahragamu. Diskusi, berbagi jadwal, dan temukan komunitas baru.'],
        ];
        @endphp

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;" class="grid-responsive-3">
            @foreach($features as $f)
            <div class="card" style="padding:32px 28px;border-left:3px solid {{ $f['color'] }};border-radius:0 8px 8px 0;position:relative;">
                <div style="font-family:'Poppins',sans-serif;font-size:2.5rem;font-weight:900;
                            color:{{ $f['color'] }}18;position:absolute;top:24px;right:24px;line-height:1;">
                    {{ $f['num'] }}
                </div>
                <div style="width:48px;height:48px;background:{{ $f['color'] }}12;border-radius:8px;
                            display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                    <i data-lucide="{{ $f['icon'] }}" style="width:22px;height:22px;color:{{ $f['color'] }};"></i>
                </div>
                <h3 style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;
                           color:#1E293B;margin:0 0 10px;">{{ $f['title'] }}</h3>
                <p style="font-size:0.875rem;color:#64748B;line-height:1.65;margin:0;">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     KOMUNITAS CABOR
═══════════════════════════════════════════════════════════ --}}
<section id="komunitas" style="padding:96px 0;background:#F8FAFC;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">

        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:40px;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:0.75rem;font-weight:700;color:#16A34A;letter-spacing:0.1em;margin-bottom:8px;">KOMUNITAS</div>
                <h2 class="section-title" style="font-size:1.875rem;margin:0 0 8px;">7 Ruang Komunitas Olahraga</h2>
                <p style="color:#64748B;font-size:0.9375rem;">Chat langsung dengan sesama pecinta olahraga Pekalongan</p>
            </div>
            <a href="/register" class="btn-brand">
                Gabung Sekarang
                <i data-lucide="arrow-right" style="width:15px;height:15px;"></i>
            </a>
        </div>

        @php
        $communities = [
            ['name'=>'Futsal',       'members'=>321, 'icon'=>'zap',         'bg'=>'#16A34A'],
            ['name'=>'Bulu Tangkis', 'members'=>187, 'icon'=>'wind',        'bg'=>'#0891B2'],
            ['name'=>'Basket',       'members'=>214, 'icon'=>'circle-dot',  'bg'=>'#EA580C'],
            ['name'=>'Tenis',        'members'=>98,  'icon'=>'target',      'bg'=>'#7C3AED'],
            ['name'=>'Padel',        'members'=>73,  'icon'=>'activity',    'bg'=>'#0F766E'],
            ['name'=>'Mini Soccer',  'members'=>156, 'icon'=>'flag',        'bg'=>'#B45309'],
            ['name'=>'Lari',         'members'=>289, 'icon'=>'footprints',  'bg'=>'#DC2626'],
        ];
        @endphp

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;" class="grid-responsive-4">
            @foreach($communities as $com)
            <a href="/register" style="text-decoration:none;">
                <div class="card" style="padding:18px 20px;display:flex;align-items:center;gap:12px;
                            border-left:3px solid {{ $com['bg'] }};border-radius:0 8px 8px 0;
                            transition:transform 0.2s;"
                     onmouseover="this.style.transform='translateY(-3px)'"
                     onmouseout="this.style.transform=''">
                    <div style="width:40px;height:40px;background:{{ $com['bg'] }}15;border-radius:8px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $com['icon'] }}" style="width:18px;height:18px;color:{{ $com['bg'] }};"></i>
                    </div>
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;color:#1E293B;">{{ $com['name'] }}</div>
                        <div style="font-size:0.75rem;color:#64748B;margin-top:2px;">{{ number_format($com['members']) }} anggota</div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     CTA SECTION
═══════════════════════════════════════════════════════════ --}}
<section style="padding:96px 0;background:#0B2415;">
    <div style="max-width:960px;margin:0 auto;padding:0 24px;">
        <div style="border:1px solid rgba(22,163,74,0.2);border-radius:12px;padding:64px 48px;text-align:center;">
            <div style="display:inline-block;background:rgba(22,163,74,0.12);border:1px solid rgba(22,163,74,0.25);
                        border-radius:4px;padding:5px 14px;margin-bottom:20px;">
                <span style="font-size:0.75rem;font-weight:700;color:#4ADE80;letter-spacing:0.08em;">BERGABUNG SEKARANG</span>
            </div>
            <h2 style="font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:#fff;margin:0 0 14px;line-height:1.2;">
                Siap Berolahraga Lebih
                <span style="color:#4ADE80;"> Mudah & Seru?</span>
            </h2>
            <p style="color:#86EFAC;font-size:0.9375rem;margin:0 auto 36px;max-width:480px;line-height:1.65;">
                Bergabung dengan ribuan pemuda Pekalongan yang sudah menggunakan SPORTA.
            </p>
            <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
                <a href="/register" class="btn-brand" style="padding:13px 32px;font-size:1rem;">
                    Daftar Gratis Sekarang
                    <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                </a>
                <a href="/login" class="btn-outline" style="padding:12px 32px;font-size:1rem;color:#fff;border-color:rgba(255,255,255,0.25);"
                   onmouseover="this.style.background='rgba(255,255,255,0.08)'"
                   onmouseout="this.style.background='transparent'">
                    Sudah punya akun? Masuk
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ --}}
<footer style="background:#0F2E1C;padding:64px 0 32px;border-top:1px solid rgba(255,255,255,0.05);">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">
        <div class="footer-grid" style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:48px;margin-bottom:48px;">
            {{-- Brand & About --}}
            <div>
                <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:36px; object-fit:contain; margin-bottom:20px;">
                <p style="font-size:0.9375rem;color:#A7F3D0;line-height:1.7;margin:0 0 20px;max-width:340px;">
                    Booking lapangan, cari partner main, dan gabung komunitas olahraga dalam satu platform digital untuk pemuda Pekalongan.
                </p>
                <div style="display:inline-block;background:rgba(22,163,74,0.15);border:1px solid rgba(22,163,74,0.3);padding:6px 12px;border-radius:4px;">
                    <p style="font-size:0.75rem;font-weight:600;color:#4ADE80;letter-spacing:0.05em;margin:0;">
                        JAMBORE PEMUDA 2026 &mdash; DINPARBUDPORA
                    </p>
                </div>
            </div>
            
            {{-- Quick Links --}}
            <div>
                <h4 style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:600;color:#fff;margin:0 0 20px;">Menu Utama</h4>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <a href="#lapangan-populer" style="font-size:0.875rem;color:#86EFAC;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#86EFAC'">Cari Lapangan</a>
                    <a href="#fitur" style="font-size:0.875rem;color:#86EFAC;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#86EFAC'">Fitur SPORTA</a>
                    <a href="#komunitas" style="font-size:0.875rem;color:#86EFAC;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#86EFAC'">Komunitas Olahraga</a>
                </div>
            </div>

            {{-- Support & Legal --}}
            <div>
                <h4 style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:600;color:#fff;margin:0 0 20px;">Bantuan & Legal</h4>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    <a href="#" style="font-size:0.875rem;color:#86EFAC;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#86EFAC'">Pusat Bantuan</a>
                    <a href="#" style="font-size:0.875rem;color:#86EFAC;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#86EFAC'">Syarat & Ketentuan</a>
                    <a href="#" style="font-size:0.875rem;color:#86EFAC;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#86EFAC'">Kebijakan Privasi</a>
                </div>
            </div>
        </div>

        {{-- Bottom Copyright --}}
        <div style="border-top:1px solid rgba(22,163,74,0.2);padding-top:24px;
                    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <p style="font-size:0.875rem;color:#6EE7B7;margin:0;">
                &copy; {{ date('Y') }} SPORTA. Karya Putra-Putri Kota Pekalongan.
            </p>
            <div style="display:flex;gap:16px;">
                <a href="#" style="color:#6EE7B7;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#6EE7B7'"><i data-lucide="instagram" style="width:20px;height:20px;"></i></a>
                <a href="#" style="color:#6EE7B7;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#6EE7B7'"><i data-lucide="facebook" style="width:20px;height:20px;"></i></a>
                <a href="#" style="color:#6EE7B7;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#6EE7B7'"><i data-lucide="twitter" style="width:20px;height:20px;"></i></a>
            </div>
        </div>
    </div>
</footer>

{{-- Responsive styles untuk landing page --}}
<style>
/* ── Tablet: hero satu kolom, ilustrasi kanan disembunyikan ── */
@media (max-width: 1024px) {
    .hero-right { display: none !important; }
    .hero-grid {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
    }
}

/* ── Mobile (≤768px) ── */
@media (max-width: 768px) {
    /* Hero section padding */
    .hero-bg section, .hero-bg > div > div:first-child {
        padding-top: 56px !important;
        padding-bottom: 72px !important;
        padding-left: 16px !important;
        padding-right: 16px !important;
    }

    /* Hero H1 ukuran */
    .hero-grid > div > h1 {
        font-size: 2rem !important;
    }

    /* Search form: satu kolom */
    .hero-search-form {
        flex-direction: column !important;
        max-width: 100% !important;
    }
    .hero-search-form > select,
    .hero-search-form > input {
        width: 100% !important;
        min-width: 0 !important;
    }
    .hero-search-form > button {
        width: 100% !important;
        justify-content: center !important;
    }

    /* Sections padding */
    section { padding-top: 48px !important; padding-bottom: 48px !important; }
    section > div { padding-left: 16px !important; padding-right: 16px !important; }

    /* Grid 3 kolom → 1 kolom */
    .grid-responsive-3 {
        grid-template-columns: 1fr !important;
    }

    /* Grid 4 kolom → 2 kolom */
    .grid-responsive-4 {
        grid-template-columns: repeat(2, 1fr) !important;
    }

    /* Section header: stack vertikal */
    #komunitas > div > div:first-child,
    #lapangan-populer > div > div:first-child {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 12px !important;
    }

    /* Footer */
    .footer-grid {
        grid-template-columns: 1fr !important;
        gap: 32px !important;
    }
}

/* ── Small Mobile (≤480px) ── */
@media (max-width: 480px) {
    .grid-responsive-4 {
        grid-template-columns: 1fr !important;
    }
    /* Section title ukuran */
    .section-title { font-size: 1.25rem !important; }
}
</style>

@endsection
