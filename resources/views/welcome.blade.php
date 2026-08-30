@extends('layouts.app')

@section('title', 'SPORTA')
@section('meta_description', 'SPORTA — Platform Digital Booking Lapangan, Cari Partner & Komunitas Olahraga Kota Pekalongan. Satu platform untuk semua kebutuhan olahraga kamu.')

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
<section class="hero-bg" style="min-height:100vh;display:flex;align-items:center;position:relative;">

    {{-- Background decorative elements --}}
    <div style="position:absolute;top:0;right:0;width:55%;height:100%;
                background:rgba(22,163,74,0.06);clip-path:polygon(15% 0,100% 0,100% 100%,0 100%);"></div>
    <div style="position:absolute;bottom:-1px;left:0;right:0;height:60px;
                background:#F8FAFC;clip-path:ellipse(55% 100% at 50% 100%);"></div>

    {{-- Decorative circles --}}
    <div style="position:absolute;top:15%;right:8%;width:320px;height:320px;
                border:1px solid rgba(22,163,74,0.15);border-radius:50%;pointer-events:none;"></div>
    <div style="position:absolute;top:25%;right:18%;width:180px;height:180px;
                border:1px solid rgba(22,163,74,0.1);border-radius:50%;pointer-events:none;"></div>

    <div style="max-width:1280px;margin:0 auto;padding:80px 24px 120px;width:100%;position:relative;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:center;" class="hero-grid">

            {{-- Left: Copy --}}
            <div>
                <div style="display:inline-flex;align-items:center;gap:8px;
                            background:rgba(22,163,74,0.15);border:1px solid rgba(22,163,74,0.3);
                            border-radius:4px;padding:5px 12px;margin-bottom:24px;">
                    <i data-lucide="map-pin" style="width:13px;height:13px;color:#4ADE80;"></i>
                    <span style="font-size:0.75rem;font-weight:600;color:#4ADE80;letter-spacing:0.05em;">KOTA PEKALONGAN</span>
                </div>

                <h1 style="font-family:'Poppins',sans-serif;font-size:clamp(2rem,5vw,3.25rem);
                           font-weight:800;color:#fff;line-height:1.15;margin:0 0 20px;">
                    Satu Platform<br>
                    <span style="color:#4ADE80;">Semua Lapangan</span><br>
                    Olahraga Kamu
                </h1>

                <p style="font-size:1.0625rem;color:#A7F3D0;line-height:1.7;margin:0 0 36px;max-width:480px;">
                    Booking lapangan, cari partner main, dan gabung komunitas olahraga —
                    semuanya dalam satu platform digital untuk pemuda Pekalongan.
                </p>

                {{-- Quick search bar --}}
                <form action="/player/booking" method="GET" class="hero-search-form"
                      style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);
                             border-radius:8px;padding:6px;display:flex;gap:6px;margin-bottom:28px;
                             max-width:520px;flex-wrap:wrap;">
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
                    <input type="text" name="location" placeholder="Lokasi / Kecamatan"
                           style="flex:1.2;min-width:120px;background:transparent;border:none;outline:none;
                                  color:#fff;font-size:0.875rem;padding:8px 10px;
                                  font-family:'Inter',sans-serif;">
                    <button type="submit" class="btn-brand" style="border-radius:6px;white-space:nowrap;">
                        <i data-lucide="search" style="width:15px;height:15px;"></i>
                        Cari Lapangan
                    </button>
                </form>

                {{-- Stats --}}
                <div style="display:flex;gap:24px;flex-wrap:wrap;">
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:#fff;">42+</div>
                        <div style="font-size:0.8125rem;color:#86EFAC;">Lapangan Terdaftar</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.1);"></div>
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:#fff;">6</div>
                        <div style="font-size:0.8125rem;color:#86EFAC;">Cabang Olahraga</div>
                    </div>
                    <div style="width:1px;background:rgba(255,255,255,0.1);"></div>
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:#fff;">1.200+</div>
                        <div style="font-size:0.8125rem;color:#86EFAC;">Pengguna Aktif</div>
                    </div>
                </div>
            </div>

            {{-- Right: Floating card illustration --}}
            <div class="hero-right" style="display:flex;flex-direction:column;gap:14px;position:relative;">

                {{-- Main card --}}
                <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
                            border-radius:12px;padding:20px;backdrop-filter:blur(4px);">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                        <div style="width:42px;height:42px;background:#16A34A;border-radius:10px;
                                    display:flex;align-items:center;justify-content:center;">
                            <i data-lucide="calendar-check" style="width:20px;height:20px;color:#fff;"></i>
                        </div>
                        <div>
                            <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:0.9375rem;">
                                Booking Dikonfirmasi
                            </div>
                            <div style="font-size:0.8125rem;color:#86EFAC;">Futsal Planet • Sabtu, 28 Agt</div>
                        </div>
                        <span class="badge badge-success" style="margin-left:auto;">✓ Terkonfirmasi</span>
                    </div>
                    <div style="display:flex;gap:12px;font-size:0.8125rem;color:#CBD5E1;">
                        <span><i data-lucide="clock" style="width:13px;height:13px;display:inline;"></i> 16:00 – 17:00</span>
                        <span><i data-lucide="users" style="width:13px;height:13px;display:inline;"></i> 5/10 Pemain</span>
                        <span><i data-lucide="wallet" style="width:13px;height:13px;display:inline;"></i> Rp 45.000</span>
                    </div>
                </div>

                {{-- Open Match card --}}
                <div style="background:rgba(234,88,12,0.15);border:1px solid rgba(234,88,12,0.3);
                            border-radius:12px;padding:18px;">
                    <div style="font-size:0.75rem;font-weight:600;color:#FB923C;letter-spacing:0.05em;margin-bottom:8px;">
                        OPEN MATCH — BUTUH PARTNER
                    </div>
                    <div style="font-family:'Poppins',sans-serif;font-weight:700;color:#fff;font-size:0.9375rem;margin-bottom:12px;">
                        GOR Bulu Tangkis Arinda
                    </div>
                    <div class="progress-bar" style="margin-bottom:6px;">
                        <div class="progress-bar-fill" style="width:60%;"></div>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.8rem;color:#CBD5E1;">
                        <span>3/5 Pemain Bergabung</span>
                        <span style="color:#FB923C;font-weight:600;">Rp 22.000/orang</span>
                    </div>
                </div>

                {{-- Community card --}}
                <div style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);
                            border-radius:12px;padding:16px;display:flex;align-items:center;gap:14px;">
                    <div style="display:flex;gap:-6px;">
                        @foreach(['F','A','R','D'] as $i => $letter)
                        <div style="width:30px;height:30px;border-radius:50%;background:hsl({{ $i * 40 + 120 }},60%,45%);
                                    display:flex;align-items:center;justify-content:center;
                                    font-size:0.6875rem;font-weight:700;color:#fff;
                                    border:2px solid #1a1a1a;{{ $i > 0 ? 'margin-left:-6px;' : '' }}">
                            {{ $letter }}
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <div style="font-size:0.8125rem;font-weight:600;color:#fff;">Komunitas Futsal Pekalongan</div>
                        <div style="font-size:0.75rem;color:#94A3B8;">148 anggota aktif</div>
                    </div>
                    <a href="/register" class="btn-brand btn-sm" style="margin-left:auto;white-space:nowrap;">Gabung</a>
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
                    :href="$field['href']"
                />
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FITUR UNGGULAN
═══════════════════════════════════════════════════════════ --}}
<section id="fitur" style="padding:80px 0;background:#fff;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">

        <div style="text-align:center;margin-bottom:52px;">
            <div style="font-size:0.8125rem;font-weight:600;color:#16A34A;letter-spacing:0.05em;margin-bottom:6px;">FITUR PLATFORM</div>
            <h2 class="section-title" style="font-size:1.75rem;">Semua yang Kamu Butuhkan</h2>
            <p style="color:#64748B;margin-top:8px;max-width:540px;margin-left:auto;margin-right:auto;line-height:1.65;">
                Dari booking lapangan hingga cari teman main, SPORTA hadir sebagai teman olahraga digital kamu.
            </p>
        </div>

        @php
        $features = [
            ['icon'=>'calendar-check', 'title'=>'Booking Lapangan',    'color'=>'#16A34A',
             'desc'=>'Pesan lapangan favoritmu dalam hitungan detik. Cek jadwal real-time, bandingkan harga, dan konfirmasi langsung.'],
            ['icon'=>'users',          'title'=>'Cari Partner (Open Match)', 'color'=>'#EA580C',
             'desc'=>'Kurang pemain? Buka slot Open Match dan bagi biaya sewa secara otomatis. Sistem yang mengatur semua kalkulasinya.'],
            ['icon'=>'message-circle', 'title'=>'Komunitas Olahraga',  'color'=>'#2563EB',
             'desc'=>'Bergabung di ruang chat khusus cabang olahragamu. Diskusi, berbagi jadwal, dan temukan komunitas baru.'],
        ];
        @endphp

        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:24px;" class="grid-responsive-3">
            @foreach($features as $f)
            <div class="card" style="padding:28px;border-radius:12px;">
                <div style="width:52px;height:52px;background:{{ $f['color'] }}15;border-radius:10px;
                            display:flex;align-items:center;justify-content:center;margin-bottom:20px;">
                    <i data-lucide="{{ $f['icon'] }}" style="width:24px;height:24px;color:{{ $f['color'] }};"></i>
                </div>
                <h3 style="font-family:'Poppins',sans-serif;font-size:1.0625rem;font-weight:700;
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
<section id="komunitas" style="padding:80px 0;background:#F8FAFC;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">

        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:36px;flex-wrap:wrap;gap:16px;">
            <div>
                <div style="font-size:0.8125rem;font-weight:600;color:#16A34A;letter-spacing:0.05em;margin-bottom:6px;">KOMUNITAS</div>
                <h2 class="section-title" style="font-size:1.75rem;">7 Ruang Komunitas Olahraga</h2>
                <p style="color:#64748B;margin-top:6px;">Chat langsung dengan sesama pecinta olahraga Pekalongan</p>
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

        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;" class="grid-responsive-4">
            @foreach($communities as $com)
            <a href="/register" style="text-decoration:none;">
                <div class="card" style="padding:20px;display:flex;align-items:center;gap:14px;
                            transition:box-shadow 0.2s,transform 0.2s;cursor:pointer;"
                     onmouseover="this.style.boxShadow='0 4px 16px rgba(0,0,0,0.08)';this.style.transform='translateY(-2px)'"
                     onmouseout="this.style.boxShadow='';this.style.transform=''">
                    <div style="width:44px;height:44px;background:{{ $com['bg'] }};border-radius:10px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $com['icon'] }}" style="width:20px;height:20px;color:#fff;"></i>
                    </div>
                    <div>
                        <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;">{{ $com['name'] }}</div>
                        <div style="font-size:0.8rem;color:#64748B;">{{ number_format($com['members']) }} anggota</div>
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
<section style="padding:80px 0;background:#0F2E1C;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;text-align:center;">
        <h2 style="font-family:'Poppins',sans-serif;font-size:2rem;font-weight:800;color:#fff;margin:0 0 14px;">
            Siap Berolahraga Lebih <span style="color:#4ADE80;">Mudah & Seru?</span>
        </h2>
        <p style="color:#86EFAC;font-size:1rem;margin:0 0 36px;">
            Bergabung dengan ribuan pemuda Pekalongan yang sudah menggunakan SPORTA.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="/register" class="btn-brand" style="padding:13px 28px;font-size:1rem;">
                Daftar Gratis Sekarang
                <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
            </a>
            <a href="/login" class="btn-outline" style="padding:12px 28px;font-size:1rem;color:#fff;border-color:rgba(255,255,255,0.3);"
               onmouseover="this.style.background='rgba(255,255,255,0.1)'"
               onmouseout="this.style.background='transparent'">
                Sudah punya akun? Masuk
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     FOOTER
═══════════════════════════════════════════════════════════ --}}
<footer style="background:#1E293B;padding:36px 0 24px;">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:30px; object-fit:contain;">
            </div>
            <p style="font-size:0.8125rem;color:#64748B;text-align:right;">
                Platform Olahraga Digital Kota Pekalongan<br>
                Jambore Pemuda 2026 — DINPARBUDPORA
            </p>
        </div>
        <div style="border-top:1px solid rgba(255,255,255,0.07);padding-top:20px;
                    display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
            <p style="font-size:0.8rem;color:#475569;margin:0;">© 2026 SPORTA. Karya Putra-Putri Kota Pekalongan.</p>
            <div style="display:flex;gap:20px;">
                <a href="#" style="font-size:0.8rem;color:#475569;text-decoration:none;">Tentang</a>
                <a href="#" style="font-size:0.8rem;color:#475569;text-decoration:none;">Syarat & Ketentuan</a>
                <a href="#" style="font-size:0.8rem;color:#475569;text-decoration:none;">Kontak</a>
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
    footer > div > div:first-child {
        flex-direction: column !important;
        gap: 12px !important;
    }
    footer > div > div:first-child > p {
        text-align: left !important;
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
