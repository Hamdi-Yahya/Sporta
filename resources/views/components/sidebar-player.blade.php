{{-- ─── Sidebar Player Navigation (§3.5) ─────────────────────────
     Menu: Beranda, Booking, Open Match, Komunitas, Riwayat, Profil
     Active state: .active class + latar hijau tua #166534
─────────────────────────────────────────────────────────────── --}}

@php $current = request()->route()->getName() ?? ''; @endphp

<ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">

    {{-- Beranda --}}
    <li>
        <a href="/player/dashboard"
           class="sidebar-link {{ str_starts_with($current, 'player.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Beranda</span>
        </a>
    </li>

    {{-- Booking Lapangan --}}
    <li>
        <a href="/player/booking"
           class="sidebar-link {{ str_starts_with($current, 'player.booking') ? 'active' : '' }}">
            <i data-lucide="calendar-check" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Booking Lapangan</span>
        </a>
    </li>

    {{-- Cari Partner / Open Match --}}
    <li>
        <a href="/player/open-match"
           class="sidebar-link {{ str_starts_with($current, 'player.open-match') ? 'active' : '' }}">
            <i data-lucide="users" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Cari Partner</span>
        </a>
    </li>

    {{-- Komunitas --}}
    <li>
        <a href="/player/community"
           class="sidebar-link {{ str_starts_with($current, 'player.community') ? 'active' : '' }}">
            <i data-lucide="message-circle" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Komunitas</span>
        </a>
    </li>

    {{-- Riwayat & Rating --}}
    <li>
        <a href="/player/history"
           class="sidebar-link {{ str_starts_with($current, 'player.history') ? 'active' : '' }}">
            <i data-lucide="clock" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Riwayat Booking</span>
        </a>
    </li>

    {{-- Profil & Notifikasi --}}
    <li>
        <a href="/player/profile"
           class="sidebar-link {{ str_starts_with($current, 'player.profile') ? 'active' : '' }}">
            <i data-lucide="user" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Profil & Notifikasi</span>
        </a>
    </li>
</ul>
