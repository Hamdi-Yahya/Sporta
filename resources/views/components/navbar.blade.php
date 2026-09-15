{{-- ─── Navbar Publik (Landing Page) ─────────────────────────────
     Logo + navigation links + tombol Login/Register
     Responsif: hamburger menu di mobile
─────────────────────────────────────────────────────────────── --}}
<style>
    .nav-menu-container { display: flex; align-items: center; gap: 32px; }
    .nav-links { display: flex; list-style: none; margin: 0; padding: 0; align-items: center; gap: 32px; }
    .nav-auth-btns { display: flex; align-items: center; gap: 10px; }
    .nav-hamburger { display: none; }
    .logo-mobile { display: none; }
    .nav-overlay { display: none; }

    @media (max-width: 768px) {
        .logo-desktop { display: none; }
        .logo-mobile { display: block; }
        .nav-hamburger { display: flex; z-index: 1001; position: relative; }
        
        /* Overlay background */
        .nav-overlay {
            display: block;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .nav-mobile-open .nav-overlay {
            opacity: 1;
            pointer-events: auto;
        }

        /* Sidebar kiri */
        .nav-menu-container {
            position: fixed;
            top: 0;
            left: -320px;
            width: 280px;
            height: 100vh;
            background: #0B2415;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            padding: 80px 32px 32px;
            gap: 40px;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 4px 0 24px rgba(0,0,0,0.5);
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        .nav-mobile-open .nav-menu-container {
            left: 0;
        }

        .nav-links {
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
            gap: 24px;
        }
        .nav-links li {
            width: 100%;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding-bottom: 12px;
        }
        .nav-links a {
            font-size: 1.0625rem !important;
            font-weight: 500;
        }

        .nav-auth-btns {
            flex-direction: column;
            width: 100%;
            gap: 16px;
        }
        .nav-auth-btns a {
            width: 100%;
            text-align: center;
            padding: 12px;
            font-size: 1rem;
        }
    }
</style>

<header id="main-navbar" style="position:sticky;top:0;z-index:100;background:rgba(15,46,28,0.92);backdrop-filter:blur(8px);border-bottom:1px solid rgba(255,255,255,0.06);">
    <nav style="max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:64px;position:relative;">

        {{-- Logo --}}
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" class="logo-desktop" style="height:34px; object-fit:contain;">
            <img src="{{ asset('images/sportahp.png') }}" alt="SPORTA Logo" class="logo-mobile" style="height:34px; object-fit:contain;">
        </a>

        <div class="nav-overlay" onclick="toggleMobileNav()"></div>

        <div class="nav-menu-container">
            {{-- Navigation Links --}}
            <ul class="nav-links">
                <li><a href="#lapangan-populer" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Lapangan</a></li>
                <li><a href="#fitur" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Fitur</a></li>
                <li><a href="#komunitas" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Komunitas</a></li>
            </ul>

            {{-- Auth buttons --}}
            <div class="nav-auth-btns">
                <a href="/login" class="btn-outline btn-sm" style="color:#fff;border-color:rgba(255,255,255,0.3);"
                   onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.borderColor='rgba(255,255,255,0.5)'"
                   onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,0.3)'">
                    Masuk
                </a>
                <a href="/register" class="btn-brand btn-sm">
                    Daftar Gratis
                </a>
            </div>
        </div>

        {{-- Hamburger (mobile) --}}
        <button class="nav-hamburger" id="nav-hamburger-btn"
            style="background:none;border:none;cursor:pointer;padding:4px;color:#fff;"
            onclick="toggleMobileNav()">
            <i data-lucide="menu" style="width:24px;height:24px;"></i>
        </button>
    </nav>
</header>

<script>
    // Toggle mobile nav
    function toggleMobileNav() {
        const nav = document.getElementById('main-navbar');
        nav.classList.toggle('nav-mobile-open');
        const btn = document.getElementById('nav-hamburger-btn');
        const isOpen = nav.classList.contains('nav-mobile-open');
        btn.innerHTML = isOpen
            ? '<i data-lucide="x" style="width:24px;height:24px;"></i>'
            : '<i data-lucide="menu" style="width:24px;height:24px;"></i>';
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
</script>
