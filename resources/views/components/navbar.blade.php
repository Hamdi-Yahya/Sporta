{{-- ─── Navbar Publik (Landing Page) ─────────────────────────────
     Logo + navigation links + tombol Login/Register
─────────────────────────────────────────────────────────────── --}}
<header style="position:sticky;top:0;z-index:100;background:rgba(15,46,28,0.92);backdrop-filter:blur(8px);border-bottom:1px solid rgba(255,255,255,0.06);">
    <nav style="max-width:1280px;margin:0 auto;padding:0 24px;display:flex;align-items:center;justify-content:space-between;height:64px;">

        {{-- Logo --}}
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;">
            <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:34px; object-fit:contain;">
        </a>

        {{-- Navigation Links (desktop) --}}
        <ul style="list-style:none;margin:0;padding:0;display:flex;align-items:center;gap:32px;" class="nav-links">
            <li><a href="#lapangan-populer" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Lapangan</a></li>
            <li><a href="#fitur" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Fitur</a></li>
            <li><a href="#komunitas" style="color:#CBD5E1;font-size:0.875rem;font-weight:500;text-decoration:none;transition:color 0.15s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#CBD5E1'">Komunitas</a></li>
        </ul>

        {{-- Auth buttons --}}
        <div style="display:flex;align-items:center;gap:10px;">
            <a href="/login" class="btn-outline btn-sm" style="color:#fff;border-color:rgba(255,255,255,0.3);"
               onmouseover="this.style.background='rgba(255,255,255,0.1)';this.style.borderColor='rgba(255,255,255,0.5)'"
               onmouseout="this.style.background='transparent';this.style.borderColor='rgba(255,255,255,0.3)'">
                Masuk
            </a>
            <a href="/register" class="btn-brand btn-sm">
                Daftar Gratis
            </a>
        </div>
    </nav>
</header>
