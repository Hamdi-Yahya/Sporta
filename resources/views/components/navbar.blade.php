{{-- ─── Navbar Publik (Landing Page) ─────────────────────────────
     Logo + navigation links + tombol Login/Register
     Responsif: hamburger menu di mobile
─────────────────────────────────────────────────────────────── --}}
<style>
    .nav-links { display: flex; }
    .nav-auth-btns { display: flex; }
    .nav-hamburger { display: none; }

    @media (max-width: 768px) {
        .nav-links { display: none; }
        .nav-auth-btns { display: none; }
        .nav-hamburger { display: flex; }
        .nav-mobile-open .nav-links {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: 64px; left: 0; right: 0;
            background: rgba(15,46,28,0.98);
            padding: 16px 24px;
            gap: 16px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .nav-mobile-open .nav-auth-btns {
            display: flex;
            flex-direction: column;
            position: absolute;
            top: calc(64px + 140px); left: 0; right: 0;
            background: rgba(15,46,28,0.98);
            padding: 12px 24px 20px;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
    }
</style>

<header id="main-navbar" style="position:sticky;top:0;z-index:100;background:rgba(15,46,28,0.92);backdrop-filter:blur(8px);border-bottom:1px solid rgba(255,255,255,0.06);">
    <nav style="max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:64px;position:relative;">

        {{-- Logo --}}
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:34px; object-fit:contain;">
        </a>

        {{-- Navigation Links (desktop) --}}
        <ul class="nav-links" style="list-style:none;margin:0;padding:0;align-items:center;gap:32px;">
            <li><a href="#lapangan-populer" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Lapangan</a></li>
            <li><a href="#fitur" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Fitur</a></li>
            <li><a href="#komunitas" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Komunitas</a></li>
        </ul>

        {{-- Auth buttons (desktop) --}}
        <div class="nav-auth-btns" style="align-items:center;gap:10px;">
            <a href="/login" class="btn-outline btn-sm" style="color:#fff;border-color:rgba(255,255,255,0.3);"
               onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.borderColor='rgba(255,255,255,0.5)'"
               onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,0.3)'">
                Masuk
            </a>
            <a href="/register" class="btn-brand btn-sm">
                Daftar Gratis
            </a>
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
