<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SPORTA — Dashboard">
    <title>@yield('title', 'Dashboard') — SPORTA</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background-color:#F8FAFC;">

    {{-- ─── Overlay backdrop untuk mobile ─────────────────────────── --}}
    <div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- ─── Sidebar ──────────────────────────────────────────────── --}}
    <div class="sidebar" id="sidebar">
        {{-- Logo --}}
        <div style="padding:24px 20px 16px; border-bottom:1px solid rgba(255,255,255,0.07);display:flex;align-items:center;justify-content:space-between;">
            <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
                <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:34px; object-fit:contain;">
            </a>
            {{-- Close button (mobile only) --}}
            <button onclick="closeSidebar()" class="hide-desktop"
                style="background:none;border:none;cursor:pointer;padding:4px;color:#94A3B8;">
                <i data-lucide="x" style="width:20px;height:20px;"></i>
            </button>
        </div>

        {{-- Role label --}}
        <div style="padding:12px 20px 8px;">
            <span style="font-size:0.6875rem;font-weight:600;letter-spacing:0.08em;text-transform:uppercase;color:#475569;">
                @yield('sidebar_role', 'Player')
            </span>
        </div>

        {{-- Navigation --}}
        <nav style="padding:0 12px;flex:1;">
            @yield('sidebar_nav')
        </nav>

        {{-- Logout --}}
        <div style="padding:16px 12px;border-top:1px solid rgba(255,255,255,0.07);">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <a href="#" onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                   class="sidebar-link" style="color:#94A3B8;">
                    <i data-lucide="log-out" style="width:17px;height:17px;flex-shrink:0;"></i>
                    <span>Keluar</span>
                </a>
            </form>
        </div>
    </div>

    {{-- ─── Main Content ─────────────────────────────────────────── --}}
    <div class="dashboard-content">

        {{-- Topbar --}}
        <div class="topbar">
            {{-- Mobile menu toggle --}}
            <button id="sidebar-toggle"
                style="background:none;border:none;cursor:pointer;padding:4px;display:none;"
                onclick="openSidebar()">
                <i data-lucide="menu" style="width:22px;height:22px;color:#1E293B;"></i>
            </button>

            <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:1rem;color:#1E293B;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                @yield('page_title', 'Dashboard')
            </div>

            <div style="display:flex;align-items:center;gap:16px;flex-shrink:0;">
                {{-- Notification bell --}}
                <a href="#" style="position:relative;display:flex;align-items:center;color:#64748B;text-decoration:none;">
                    <i data-lucide="bell" style="width:20px;height:20px;"></i>
                    @php $unread = Auth::check() ? Auth::user()->notifikasis()->belumDibaca()->count() : 0; @endphp
                    @if($unread > 0)
                    <span class="notif-badge">{{ $unread }}</span>
                    @endif
                </a>

                {{-- Avatar --}}
                <div style="width:34px;height:34px;border-radius:50%;background:#16A34A;display:flex;align-items:center;justify-content:center;cursor:pointer;flex-shrink:0;">
                    <span style="font-family:'Poppins',sans-serif;font-size:0.8125rem;font-weight:700;color:#fff;">
                        {{ Auth::check() ? strtoupper(substr(Auth::user()->name, 0, 1)) : 'U' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Page content --}}
        <div class="page-content" style="padding:24px 28px;">
            @yield('content')
        </div>
    </div>

    <script>
        lucide.createIcons();

        const mq = window.matchMedia('(max-width: 768px)');
        const toggle = document.getElementById('sidebar-toggle');

        // Tampilkan tombol hamburger di mobile
        function handleMQ(e) {
            toggle.style.display = e.matches ? 'flex' : 'none';
        }
        mq.addEventListener('change', handleMQ);
        handleMQ(mq);

        // Buka sidebar + overlay
        function openSidebar() {
            document.getElementById('sidebar').classList.add('open');
            document.getElementById('sidebar-overlay').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        // Tutup sidebar + overlay
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-overlay').classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>
    @stack('scripts')
</body>
</html>

