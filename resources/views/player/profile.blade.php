@extends('layouts.dashboard')

@section('title', 'Profil & Notifikasi')
@section('page_title', 'Profil & Notifikasi')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
$notifications = [
    ['icon'=>'check-circle', 'color'=>'#16A34A', 'bg'=>'#DCFCE7',
     'title'=>'Booking Dikonfirmasi', 'desc'=>'Booking Futsal Planet Pekalongan (28 Agt 16:00) telah dikonfirmasi oleh pemilik lapangan.',
     'time'=>'5 menit lalu', 'read'=>false],
    ['icon'=>'alert-triangle','color'=>'#F59E0B','bg'=>'#FEF3C7',
     'title'=>'Segera Upload Bukti Transfer','desc'=>'Booking Bulu Tangkis Arinda akan kedaluwarsa dalam 15 menit.',
     'time'=>'18 menit lalu','read'=>false],
    ['icon'=>'users',        'color'=>'#EA580C', 'bg'=>'#FFF7ED',
     'title'=>'Open Match Hampir Penuh','desc'=>'Slot Futsal 17:00 di Futsal Planet sudah 9/10 terisi.',
     'time'=>'1 jam lalu',  'read'=>true],
    ['icon'=>'x-circle',     'color'=>'#DC2626', 'bg'=>'#FEE2E2',
     'title'=>'Booking Ditolak','desc'=>'Booking Lapangan Basket Pemuda ditolak. Alasan: Bukti transfer tidak terbaca jelas.',
     'time'=>'2 hari lalu', 'read'=>true],
    ['icon'=>'star',         'color'=>'#F59E0B', 'bg'=>'#FEF3C7',
     'title'=>'Beri Rating Lapangan','desc'=>'Booking Padel Court Pekalongan sudah selesai. Bagaimana pengalamanmu?',
     'time'=>'3 hari lalu', 'read'=>true],
];
@endphp

<style>
@media (max-width: 768px) {
    .profile-layout {
        grid-template-columns: 1fr !important;
    }
}
</style>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:flex-start;" class="profile-layout">


    {{-- ─── Profil ─────────────────────────────────────────────── --}}
    <div>
        <div class="card" style="padding:24px;margin-bottom:18px;">
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;">
                <div style="width:72px;height:72px;border-radius:50%;background:#16A34A;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <span style="font-family:'Poppins',sans-serif;font-size:1.75rem;font-weight:800;color:#fff;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h2 style="font-family:'Poppins',sans-serif;font-size:1.125rem;font-weight:700;
                               color:#1E293B;margin:0 0 4px;">{{ Auth::user()->name }}</h2>
                    <p style="font-size:0.8125rem;color:#64748B;margin:0 0 4px;">{{ Auth::user()->email }}</p>
                    <span class="badge badge-success">
                        <i data-lucide="user" style="width:10px;height:10px;"></i>
                        Player / User
                    </span>
                </div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:20px;">
                @foreach([['Total Booking','12'],['Selesai','8'],['Open Match Diikuti','3'],['Rating Diberikan','8']] as $s)
                <div style="background:#F8FAFC;border-radius:8px;padding:12px;text-align:center;">
                    <div style="font-family:'Poppins',sans-serif;font-size:1.25rem;font-weight:800;color:#1E293B;">{{ $s[1] }}</div>
                    <div style="font-size:0.75rem;color:#64748B;">{{ $s[0] }}</div>
                </div>
                @endforeach
            </div>

            <button class="btn-outline" style="width:100%;">
                <i data-lucide="edit-2" style="width:15px;height:15px;"></i>
                Edit Profil
            </button>
        </div>

        {{-- Edit form --}}
        <div class="card" style="padding:22px;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 18px;">Edit Informasi Akun</h3>
            <div style="display:flex;flex-direction:column;gap:14px;">
                @foreach([
                    ['Nama Lengkap', Auth::user()->name, 'text', 'user'],
                    ['Nomor HP', Auth::user()->no_telp ?? '-', 'tel', 'smartphone'],
                    ['Kota/Kecamatan', 'Pekalongan Barat', 'text', 'map-pin'], // Sementara statis jika tidak ada di tabel users
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
                    <label class="form-label">Email</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="mail" style="width:15px;height:15px;"></i>
                        </span>
                        <input type="email" class="form-input" value="{{ Auth::user()->email }}"
                               style="padding-left:36px;background:#F8FAFC;color:#94A3B8;" readonly>
                    </div>
                    <p style="font-size:0.75rem;color:#94A3B8;margin:4px 0 0;">Email tidak dapat diubah</p>
                </div>

                <button class="btn-brand" style="align-self:flex-start;">
                    <i data-lucide="save" style="width:15px;height:15px;"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- ─── Notifikasi (FR-G1) ─────────────────────────────────── --}}
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
                Tandai semua sudah dibaca
            </button>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px;">
            @foreach($notifications as $notif)
            <div class="card" style="padding:14px 16px;{{ !$notif['read'] ? 'border-left:3px solid #16A34A;' : '' }}">
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
                            <div style="width:8px;height:8px;background:#16A34A;border-radius:50%;flex-shrink:0;margin-top:4px;"></div>
                            @endif
                        </div>
                        <p style="font-size:0.8125rem;color:#64748B;margin:4px 0;line-height:1.5;">{{ $notif['desc'] }}</p>
                        <span style="font-size:0.75rem;color:#94A3B8;">{{ $notif['time'] }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
