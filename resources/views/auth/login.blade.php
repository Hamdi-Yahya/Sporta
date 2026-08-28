@extends('layouts.app')

@section('title', 'Masuk ke SPORTA')
@section('meta_description', 'Masuk ke akun SPORTA untuk booking lapangan dan cari partner olahraga di Kota Pekalongan.')

@section('content')

<div style="min-height:100vh;display:flex;">

    {{-- ─── Left Panel (Branding) ──────────────────────────────── --}}
    <div style="flex:1;background:#0F2E1C;display:flex;flex-direction:column;
                align-items:flex-start;justify-content:center;padding:60px;
                position:relative;overflow:hidden;">

        {{-- Decorative circles --}}
        <div style="position:absolute;bottom:-40px;right:-40px;width:260px;height:260px;
                    border:1px solid rgba(22,163,74,0.2);border-radius:50%;pointer-events:none;"></div>
        <div style="position:absolute;top:-20px;right:60px;width:140px;height:140px;
                    border:1px solid rgba(22,163,74,0.15);border-radius:50%;pointer-events:none;"></div>

        {{-- Logo --}}
        <a href="/" style="display:flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:56px;">
            <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:36px; object-fit:contain;">
        </a>

        <h2 style="font-family:'Poppins',sans-serif;font-size:1.875rem;font-weight:800;
                   color:#fff;line-height:1.2;margin:0 0 16px;">
            Selamat Datang<br>Kembali!
        </h2>
        <p style="color:#86EFAC;font-size:0.9375rem;line-height:1.65;margin:0 0 40px;max-width:360px;">
            Masuk untuk mengakses booking lapangan, cari partner, dan komunitas olahraga Kota Pekalongan.
        </p>

        {{-- Feature list --}}
        <div style="display:flex;flex-direction:column;gap:14px;">
            @foreach(['Booking lapangan real-time','Open Match — cari partner main','7 komunitas cabang olahraga'] as $feat)
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:22px;height:22px;background:#16A34A;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i data-lucide="check" style="width:12px;height:12px;color:#fff;"></i>
                </div>
                <span style="font-size:0.875rem;color:#A7F3D0;">{{ $feat }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ─── Right Panel (Form) ─────────────────────────────────── --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;
                padding:48px 60px;background:#fff;">
        <div style="width:100%;max-width:420px;">

            <div style="margin-bottom:32px;">
                <h1 style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:700;
                           color:#1E293B;margin:0 0 6px;">Masuk ke Akun</h1>
                <p style="font-size:0.875rem;color:#64748B;margin:0;">
                    Belum punya akun?
                    <a href="/register" style="color:#16A34A;font-weight:600;text-decoration:none;">Daftar sekarang</a>
                </p>
            </div>

            {{-- Login Form (FR-A2) --}}
            <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:20px;">
                @csrf

                {{-- Global error message --}}
                @if($errors->any())
                <div style="background:#FEF2F2;border:1px solid #FECACA;padding:10px 14px;border-radius:6px;">
                    <p style="font-size:0.8125rem;color:#DC2626;margin:0;">{{ $errors->first() }}</p>
                </div>
                @endif

                {{-- Email --}}
                <div>
                    <label class="form-label" for="email">Alamat Email</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);
                                     color:#94A3B8;">
                            <i data-lucide="mail" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="email" id="email" name="email" class="form-input"
                               style="padding-left:38px;"
                               placeholder="nama@email.com" required autocomplete="email"
                               value="{{ old('email') }}">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                        <label class="form-label" for="password" style="margin-bottom:0;">Password</label>
                        <a href="#" style="font-size:0.8125rem;color:#16A34A;text-decoration:none;">Lupa password?</a>
                    </div>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="lock" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="password" id="password" name="password" class="form-input"
                               style="padding-left:38px;"
                               placeholder="Masukkan password" required id="login-password">
                        <button type="button" onclick="togglePass('login-password','toggle-icon')"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;cursor:pointer;color:#94A3B8;">
                            <i id="toggle-icon" data-lucide="eye" style="width:16px;height:16px;"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="checkbox" id="remember" name="remember"
                           style="width:16px;height:16px;accent-color:#16A34A;cursor:pointer;">
                    <label for="remember" style="font-size:0.875rem;color:#64748B;cursor:pointer;">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-brand" style="width:100%;padding:12px;font-size:0.9375rem;">
                    <i data-lucide="log-in" style="width:17px;height:17px;"></i>
                    Masuk
                </button>
            </form>

            {{-- Divider --}}
            <div style="display:flex;align-items:center;gap:12px;margin:24px 0;">
                <div style="flex:1;height:1px;background:#E2E8F0;"></div>
                <span style="font-size:0.8125rem;color:#94A3B8;">atau</span>
                <div style="flex:1;height:1px;background:#E2E8F0;"></div>
            </div>

            {{-- Guest hint --}}
            <p style="text-align:center;font-size:0.8125rem;color:#94A3B8;">
                Ingin coba dulu?
                <a href="/" style="color:#16A34A;font-weight:600;text-decoration:none;">Lihat landing page</a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /* Toggle visibility password */
    function togglePass(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }
        lucide.createIcons();
    }
</script>
@endpush

@endsection
