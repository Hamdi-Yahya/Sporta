@php 
    $current = request()->route()->getName() ?? ''; 
    $pendingBookingsCount = \App\Models\Booking::whereHas('slot.lapangan', function($q) {
        $q->where('owner_id', \Illuminate\Support\Facades\Auth::id());
    })->where('status', \App\Models\Booking::STATUS_MENUNGGU_VERIFIKASI)->count();
@endphp

<ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">

    {{-- Beranda --}}
    <li>
        <a href="/owner/dashboard"
           class="sidebar-link {{ str_starts_with($current, 'owner.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Beranda</span>
        </a>
    </li>

    {{-- Kelola Lapangan --}}
    <li>
        <a href="/owner/fields"
           class="sidebar-link {{ str_starts_with($current, 'owner.fields') ? 'active' : '' }}">
            <i data-lucide="map-pin" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Kelola Lapangan</span>
        </a>
    </li>

    {{-- Kelola Jadwal & Slot --}}
    <li>
        <a href="/owner/schedules"
           class="sidebar-link {{ str_starts_with($current, 'owner.schedules') ? 'active' : '' }}">
            <i data-lucide="calendar-days" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Kelola Jadwal & Slot</span>
        </a>
    </li>

    {{-- Verifikasi Booking --}}
    <li>
        <a href="/owner/verify-booking"
           class="sidebar-link {{ str_starts_with($current, 'owner.verify') ? 'active' : '' }}"
           style="position:relative;">
            <i data-lucide="shield-check" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Verifikasi Booking</span>
            {{-- Badge notif --}}
            @if($pendingBookingsCount > 0)
            <span style="margin-left:auto;background:#EA580C;color:#fff;font-size:0.6875rem;font-weight:700;
                         padding:1px 7px;border-radius:10px;">{{ $pendingBookingsCount }}</span>
            @endif
        </a>
    </li>



    {{-- Profil Usaha --}}
    <li>
        <a href="/owner/profile"
           class="sidebar-link {{ str_starts_with($current, 'owner.profile') ? 'active' : '' }}">
            <i data-lucide="building-2" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Profil Usaha</span>
        </a>
    </li>
</ul>
