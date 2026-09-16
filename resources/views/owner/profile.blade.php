@extends('layouts.dashboard')

@section('title', 'Profil Usaha')
@section('page_title', 'Profil Usaha & Notifikasi')
@section('sidebar_role', 'Owner')
@section('user_initial', 'S')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

@php
$notifications = [
    ['icon'=>'wallet',        'color'=>'#16A34A','bg'=>'#DCFCE7',
     'title'=>'Booking Baru Masuk',  'desc'=>'Ahmad Fauzan memesan slot Futsal 28 Agt 16:00–17:00. Menunggu upload bukti transfer.',
     'time'=>'5 menit lalu', 'read'=>false],
    ['icon'=>'file-search',   'color'=>'#EA580C','bg'=>'#FFF7ED',
     'title'=>'Bukti Transfer Baru', 'desc'=>'Dinda Rahayu mengunggah bukti transfer untuk booking #SPTA-002. Segera verifikasi.',
     'time'=>'20 menit lalu','read'=>false],
    ['icon'=>'users',         'color'=>'#2563EB','bg'=>'#EFF6FF',
     'title'=>'Open Match Hampir Penuh','desc'=>'Slot Open Match 29 Agt 10:00 sudah 9/10 terisi.',
     'time'=>'1 jam lalu',   'read'=>true],
    ['icon'=>'check-circle',  'color'=>'#16A34A','bg'=>'#DCFCE7',
     'title'=>'Lapangan Disetujui Admin','desc'=>'Lapangan Futsal Planet Pekalongan telah disetujui Admin dan kini tampil di pencarian publik.',
     'time'=>'2 hari lalu',  'read'=>true],
];
@endphp

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:flex-start;">

    {{-- ─── Profil Usaha ──────────────────────────────────────── --}}
    <div>
        <div class="card" style="padding:24px;margin-bottom:18px;">
            <div style="display:flex;align-items:flex-start;gap:16px;margin-bottom:22px;">
                <div style="width:72px;height:72px;border-radius:12px;background:#0F2E1C;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="building-2" style="width:32px;height:32px;color:#4ADE80;"></i>
                </div>
                <div>
                    <h2 style="font-family:'Poppins',sans-serif;font-size:1.125rem;font-weight:700;
                               color:#1E293B;margin:0 0 4px;">{{ $user->nama_usaha ?? 'Nama Usaha Belum Diatur' }}</h2>
                    <p style="font-size:0.8125rem;color:#64748B;margin:0 0 6px;">
                        {{ $user->name }} · {{ $user->email }}
                    </p>
                    <span class="badge badge-success">
                        <i data-lucide="shield-check" style="width:10px;height:10px;"></i>
                        Pemilik Lapangan Terverifikasi
                    </span>
                </div>
            </div>

            {{-- Stats --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
                @foreach([
                    ['label'=>'Lapangan Terdaftar','val'=>'1'],
                    ['label'=>'Total Booking',      'val'=>'38'],
                    ['label'=>'Pendapatan Bulan Ini','val'=>'Rp 3,4jt'],
                    ['label'=>'Rating Rata-rata',    'val'=>'4.8 ★'],
                ] as $s)
                <div style="background:#F8FAFC;border-radius:8px;padding:12px;text-align:center;">
                    <div style="font-family:'Poppins',sans-serif;font-size:1.125rem;font-weight:800;color:#1E293B;">{{ $s['val'] }}</div>
                    <div style="font-size:0.75rem;color:#64748B;">{{ $s['label'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Edit profil usaha (FR-A4) --}}
        <div class="card" style="padding:22px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 18px;">Edit Profil Usaha</h3>
            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach([
                    ['Nama Pemilik',          $user->name,              'text',  'user'],
                    ['Nama Usaha/Lapangan',   $user->nama_usaha ?? 'Nama Usaha Belum Diatur',   'text',  'building-2'],
                    ['No. HP / WhatsApp',     $user->no_telp ?? '-',                'tel',   'smartphone'],
                    ['Rekening Bank',         'BRI - 1234-5678-9012',       'text',  'credit-card'],
                ] as $field)
                <div>
                    <label class="form-label">{{ $field[0] }}</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="{{ $field[3] }}" style="width:15px;height:15px;"></i>
                        </span>
                        <input type="{{ $field[2] }}" class="form-input" value="{{ $field[1] }}"
                               style="padding-left:36px;">
                    </div>
                </div>
                @endforeach

                <div>
                    <label class="form-label">Email (tidak dapat diubah)</label>
                    <input type="email" class="form-input" value="{{ $user->email }}"
                           style="background:#F8FAFC;color:#94A3B8;" readonly>
                </div>

                <button class="btn-brand" style="align-self:flex-start;">
                    <i data-lucide="save" style="width:15px;height:15px;"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- ─── Notifikasi Owner (FR-D1) ──────────────────────────── --}}
    <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
            <h3 class="section-title" style="font-size:1rem;">
                Notifikasi
                <span style="display:inline-flex;align-items:center;justify-content:center;
                             width:20px;height:20px;background:#DC2626;border-radius:50%;
                             font-size:0.6875rem;font-weight:700;color:#fff;margin-left:6px;">
                    2
                </span>
            </h3>
            <button style="background:none;border:none;font-size:0.8125rem;color:#16A34A;font-weight:600;cursor:pointer;">
                Tandai sudah dibaca
            </button>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($notifications as $notif)
            <div class="card" style="padding:14px 16px;{{ !$notif['read'] ? 'border-left:3px solid #EA580C;' : '' }}">
                <div style="display:flex;gap:12px;">
                    <div style="width:38px;height:38px;background:{{ $notif['bg'] }};border-radius:9px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $notif['icon'] }}" style="width:17px;height:17px;color:{{ $notif['color'] }};"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                            <div style="font-family:'Poppins',sans-serif;font-weight:{{ !$notif['read'] ? '700' : '600' }};
                                        font-size:0.875rem;color:#1E293B;">
                                {{ $notif['title'] }}
                            </div>
                            @if(!$notif['read'])
                            <div style="width:8px;height:8px;background:#EA580C;border-radius:50%;flex-shrink:0;margin-top:4px;"></div>
                            @endif
                        </div>
                        <p style="font-size:0.8125rem;color:#64748B;margin:4px 0;line-height:1.5;">{{ $notif['desc'] }}</p>
                        <span style="font-size:0.75rem;color:#94A3B8;">{{ $notif['time'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Rekening info --}}
        <div class="card" style="padding:18px;margin-top:16px;background:#F0FDF4;border:1px solid #BBF7D0;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
                <i data-lucide="credit-card" style="width:18px;height:18px;color:#16A34A;"></i>
                <span style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#166534;">
                    Rekening Pembayaran
                </span>
            </div>
            <p style="font-size:0.8125rem;color:#166534;margin:0 0 6px;">
                Nomor rekening ini yang akan dilihat pemesan saat booking.
            </p>
            <div style="background:#fff;border-radius:6px;padding:12px;border:1px solid #BBF7D0;">
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#1E293B;">
                    BRI — 1234-5678-9012
                </div>
                <div style="font-size:0.8125rem;color:#64748B;">a.n. {{ $user->name }}</div>
            </div>
        </div>
    </div>
</div>

@endsection
