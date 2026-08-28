@extends('layouts.app')

@section('title', 'Daftar Akun SPORTA')
@section('meta_description', 'Daftar akun SPORTA untuk booking lapangan dan cari partner olahraga di Kota Pekalongan.')

@section('content')

<div style="min-height:100vh;display:flex;">

    {{-- ─── Left Panel (Form) ─────────────────────────────────── --}}
    <div style="flex:1;display:flex;align-items:center;justify-content:center;
                padding:48px 60px;background:#fff;overflow-y:auto;">
        <div style="width:100%;max-width:460px;">

            <a href="/" style="display:flex;align-items:center;gap:8px;text-decoration:none;margin-bottom:28px;">
                <img src="{{ asset('images/sportweb.png') }}" alt="SPORTA Logo" style="height:30px; object-fit:contain;">
            </a>

            <div style="margin-bottom:28px;">
                <h1 style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:700;
                           color:#1E293B;margin:0 0 6px;">Buat Akun Baru</h1>
                <p style="font-size:0.875rem;color:#64748B;margin:0;">
                    Sudah punya akun?
                    <a href="/login" style="color:#16A34A;font-weight:600;text-decoration:none;">Masuk di sini</a>
                </p>
            </div>

            {{-- Role Selector (FR-A1, FR-A3, FR-A4) --}}
            <div style="margin-bottom:24px;">
                <p class="form-label" style="margin-bottom:10px;">Daftar sebagai</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    {{-- Player tab --}}
                    <label for="role-player" style="cursor:pointer;">
                        <input type="radio" id="role-player" name="role" value="player"
                               style="display:none;" checked onchange="setRole(this)">
                        <div id="role-player-box"
                             style="border:2px solid #16A34A;border-radius:8px;padding:14px 16px;
                                    background:#F0FDF4;display:flex;align-items:center;gap:10px;">
                            <i data-lucide="user" style="width:18px;height:18px;color:#16A34A;"></i>
                            <div>
                                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;color:#166534;">Player / User</div>
                                <div style="font-size:0.75rem;color:#64748B;">Booking lapangan & cari partner</div>
                            </div>
                        </div>
                    </label>
                    {{-- Owner tab --}}
                    <label for="role-owner" style="cursor:pointer;">
                        <input type="radio" id="role-owner" name="role" value="owner"
                               style="display:none;" onchange="setRole(this)">
                        <div id="role-owner-box"
                             style="border:2px solid #E2E8F0;border-radius:8px;padding:14px 16px;
                                    background:#fff;display:flex;align-items:center;gap:10px;">
                            <i data-lucide="building-2" style="width:18px;height:18px;color:#64748B;"></i>
                            <div>
                                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;color:#1E293B;">Pemilik Lapangan</div>
                                <div style="font-size:0.75rem;color:#64748B;">Daftarkan & kelola lapangan</div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Register Form (FR-A1) --}}
            <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:18px;">
                @csrf
                <input type="hidden" name="role_hidden" id="role-hidden" value="{{ old('role_hidden', 'player') }}">

                {{-- Validation errors --}}
                @if($errors->any())
                <div style="background:#FEF2F2;border:1px solid #FECACA;padding:10px 14px;border-radius:6px;">
                    <ul style="margin:0;padding:0 0 0 16px;font-size:0.8125rem;color:#DC2626;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Nama Lengkap --}}
                <div>
                    <label class="form-label" for="name">Nama Lengkap</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="user" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="text" id="name" name="name" class="form-input"
                               style="padding-left:38px;"
                               placeholder="Nama lengkap kamu" required autocomplete="name"
                               value="{{ old('name') }}">
                    </div>
                </div>

                {{-- No. HP --}}
                <div>
                    <label class="form-label" for="phone">Nomor HP / WhatsApp</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="smartphone" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="tel" id="phone" name="phone" class="form-input"
                               style="padding-left:38px;"
                               placeholder="08xxxxxxxxxx" required
                               value="{{ old('phone') }}">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="form-label" for="email">Alamat Email</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
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
                    <label class="form-label" for="password">Password</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="lock" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="password" id="reg-password" name="password" class="form-input"
                               style="padding-left:38px;"
                               placeholder="Min. 8 karakter" required minlength="8">
                        <button type="button" onclick="togglePass('reg-password','reg-icon')"
                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                                       background:none;border:none;cursor:pointer;color:#94A3B8;">
                            <i id="reg-icon" data-lucide="eye" style="width:16px;height:16px;"></i>
                        </button>
                    </div>
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="lock" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input" style="padding-left:38px;"
                               placeholder="Ulangi password" required>
                    </div>
                </div>

                {{-- Nama Usaha (Owner only) --}}
                <div id="business-field" style="display:none;">
                    <label class="form-label" for="business_name">Nama Usaha Lapangan</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;">
                            <i data-lucide="building-2" style="width:16px;height:16px;"></i>
                        </span>
                        <input type="text" id="business_name" name="business_name" class="form-input"
                               style="padding-left:38px;"
                               placeholder="Nama GOR / lapangan kamu">
                    </div>
                </div>

                {{-- Terms --}}
                <div style="display:flex;align-items:flex-start;gap:8px;">
                    <input type="checkbox" id="terms" name="terms" required
                           style="width:16px;height:16px;accent-color:#16A34A;cursor:pointer;margin-top:2px;flex-shrink:0;">
                    <label for="terms" style="font-size:0.8125rem;color:#64748B;cursor:pointer;line-height:1.5;">
                        Saya menyetujui <a href="#" style="color:#16A34A;font-weight:600;">Syarat & Ketentuan</a>
                        serta <a href="#" style="color:#16A34A;font-weight:600;">Kebijakan Privasi</a> SPORTA
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-brand" style="width:100%;padding:12px;font-size:0.9375rem;margin-top:4px;">
                    <i data-lucide="user-plus" style="width:17px;height:17px;"></i>
                    Buat Akun
                </button>
            </form>
        </div>
    </div>

    {{-- ─── Right Panel (Branding) ─────────────────────────────── --}}
    <div style="flex:0.7;background:#14532D;display:flex;flex-direction:column;
                align-items:flex-start;justify-content:center;padding:60px;
                position:relative;overflow:hidden;min-width:320px;">

        <div style="position:absolute;top:-40px;left:-40px;width:240px;height:240px;
                    border:1px solid rgba(255,255,255,0.08);border-radius:50%;"></div>
        <div style="position:absolute;bottom:40px;right:-20px;width:160px;height:160px;
                    border:1px solid rgba(255,255,255,0.06);border-radius:50%;"></div>

        <div style="position:relative;z-index:1;">
            <div style="font-size:0.8rem;font-weight:600;color:#86EFAC;letter-spacing:0.06em;margin-bottom:16px;">
                KENAPA SPORTA?
            </div>
            <h2 style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;
                       color:#fff;line-height:1.3;margin:0 0 32px;">
                Olahraga lebih mudah,<br>teman lebih banyak.
            </h2>

            @php
            $benefits = [
                ['icon'=>'zap',           'text'=>'Booking instan tanpa antri'],
                ['icon'=>'divide',        'text'=>'Bagi biaya otomatis Open Match'],
                ['icon'=>'message-circle','text'=>'Chat komunitas real-time'],
                ['icon'=>'star',          'text'=>'Rating & ulasan lapangan terpercaya'],
            ];
            @endphp

            <div style="display:flex;flex-direction:column;gap:18px;">
                @foreach($benefits as $b)
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;background:rgba(22,163,74,0.25);border-radius:8px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $b['icon'] }}" style="width:17px;height:17px;color:#4ADE80;"></i>
                    </div>
                    <span style="font-size:0.875rem;color:#D1FAE5;">{{ $b['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /* Toggle role → tampilkan/sembunyikan field nama usaha */
    function setRole(radio) {
        const playerBox = document.getElementById('role-player-box');
        const ownerBox  = document.getElementById('role-owner-box');
        const bizField  = document.getElementById('business-field');
        const hiddenInput = document.getElementById('role-hidden');

        if (radio.value === 'owner') {
            ownerBox.style.border  = '2px solid #16A34A';
            ownerBox.style.background = '#F0FDF4';
            playerBox.style.border = '2px solid #E2E8F0';
            playerBox.style.background = '#fff';
            bizField.style.display = 'block';
        } else {
            playerBox.style.border = '2px solid #16A34A';
            playerBox.style.background = '#F0FDF4';
            ownerBox.style.border  = '2px solid #E2E8F0';
            ownerBox.style.background = '#fff';
            bizField.style.display = 'none';
        }
        hiddenInput.value = radio.value;
    }

    /* Toggle password visibility */
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
