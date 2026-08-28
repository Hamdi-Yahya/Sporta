@extends('layouts.dashboard')

@section('title', 'Pembayaran Booking')
@section('page_title', 'Pembayaran')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

<div style="max-width:800px;">

    {{-- ─── Step progress ───────────────────────────────────────── --}}
    <div style="display:flex;align-items:center;gap:0;margin-bottom:28px;">
        @foreach([['Pilih Slot','check'],['Konfirmasi','check'],['Pembayaran','circle-dot'],['Selesai','circle']] as $i => $step)
        <div style="display:flex;align-items:center;flex:1;">
            <div style="display:flex;align-items:center;gap:8px;">
                <div style="width:28px;height:28px;border-radius:50%;
                            background:{{ $i < 2 ? '#16A34A' : ($i === 2 ? '#1E293B' : '#E2E8F0') }};
                            display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    @if($i < 2)
                        <i data-lucide="check" style="width:14px;height:14px;color:#fff;"></i>
                    @elseif($i === 2)
                        <span style="width:8px;height:8px;background:#fff;border-radius:50%;"></span>
                    @else
                        <span style="width:8px;height:8px;border:2px solid #CBD5E1;border-radius:50%;"></span>
                    @endif
                </div>
                <span style="font-size:0.8125rem;font-weight:{{ $i <= 2 ? '600' : '400' }};
                             color:{{ $i < 2 ? '#16A34A' : ($i === 2 ? '#1E293B' : '#94A3B8') }};">
                    {{ $step[0] }}
                </span>
            </div>
            @if($i < 3)
            <div style="flex:1;height:1px;background:{{ $i < 2 ? '#16A34A' : '#E2E8F0' }};margin:0 8px;"></div>
            @endif
        </div>
        @endforeach
    </div>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:flex-start;">

        {{-- ─── Kiri: Instruksi & Upload ───────────────────────── --}}
        <div style="display:flex;flex-direction:column;gap:18px;">

            {{-- Timer countdown (§3.7 FR-C5) --}}
            <div style="background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;
                        padding:14px 16px;display:flex;align-items:center;gap:12px;">
                <i data-lucide="timer" style="width:20px;height:20px;color:#DC2626;flex-shrink:0;"></i>
                <div>
                    <div style="font-size:0.8125rem;font-weight:600;color:#991B1B;margin-bottom:2px;">
                        Selesaikan pembayaran sebelum waktu habis
                    </div>
                    <div class="countdown-timer" id="timer">29:58</div>
                </div>
                <div style="margin-left:auto;text-align:right;">
                    <div style="font-size:0.75rem;color:#DC2626;">Booking otomatis batal jika</div>
                    <div style="font-size:0.75rem;color:#DC2626;">melewati batas waktu</div>
                </div>
            </div>

            {{-- Instruksi Transfer --}}
            <div class="card" style="padding:20px;">
                <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                           color:#1E293B;margin:0 0 16px;display:flex;align-items:center;gap:7px;">
                    <i data-lucide="building" style="width:16px;height:16px;color:#16A34A;"></i>
                    Instruksi Pembayaran
                </h3>

                <div style="background:#F8FAFC;border-radius:8px;padding:16px;margin-bottom:14px;">
                    <div style="font-size:0.8125rem;color:#64748B;margin-bottom:4px;">Transfer ke rekening:</div>
                    <div style="font-family:'Poppins',sans-serif;font-size:1rem;font-weight:700;color:#1E293B;margin-bottom:2px;">
                        Bank BRI — 1234-5678-9012
                    </div>
                    <div style="font-size:0.875rem;color:#475569;">a.n. <strong>Futsal Planet Pekalongan</strong></div>
                </div>

                <div style="background:#F0FDF4;border:1px solid #BBF7D0;border-radius:8px;padding:14px;margin-bottom:14px;">
                    <div style="font-size:0.8125rem;color:#166534;margin-bottom:6px;font-weight:600;">Nominal Transfer:</div>
                    <div style="font-family:'Poppins',sans-serif;font-size:1.5rem;font-weight:800;color:#166534;">
                        Rp 90.000
                    </div>
                    <div style="font-size:0.75rem;color:#4ADE80;margin-top:4px;">
                        Transfer nominal tepat untuk memudahkan verifikasi
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach([
                        'Transfer dengan nominal TEPAT sesuai yang tertera',
                        'Simpan bukti transfer untuk diunggah di bawah',
                        'Booking dikonfirmasi setelah pemilik lapangan memverifikasi',
                    ] as $step)
                    <div style="display:flex;align-items:flex-start;gap:8px;font-size:0.8125rem;color:#475569;">
                        <i data-lucide="info" style="width:14px;height:14px;color:#64748B;flex-shrink:0;margin-top:1px;"></i>
                        {{ $step }}
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Upload Bukti Transfer (FR-C6) --}}
            <div class="card" style="padding:20px;">
                <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                           color:#1E293B;margin:0 0 14px;">Upload Bukti Transfer</h3>

                <div id="upload-area"
                     style="border:2px dashed #CBD5E1;border-radius:8px;padding:32px;
                            text-align:center;cursor:pointer;transition:border-color 0.2s,background 0.2s;"
                     onmouseover="this.style.borderColor='#16A34A';this.style.background='#F0FDF4'"
                     onmouseout="this.style.borderColor='#CBD5E1';this.style.background='transparent'"
                     onclick="document.getElementById('file-input').click()">
                    <i data-lucide="upload-cloud" style="width:36px;height:36px;color:#94A3B8;margin-bottom:8px;"></i>
                    <div style="font-weight:600;font-size:0.875rem;color:#1E293B;margin-bottom:4px;">
                        Klik untuk upload foto bukti transfer
                    </div>
                    <div style="font-size:0.8125rem;color:#94A3B8;">JPG, PNG, PDF — Maks. 5 MB</div>
                </div>
                <input type="file" id="file-input" accept="image/*,.pdf" style="display:none;"
                       onchange="handleFileSelect(this)">

                {{-- Preview file yang diupload --}}
                <div id="file-preview" style="display:none;margin-top:12px;padding:12px;
                     background:#F0FDF4;border:1px solid #BBF7D0;border-radius:6px;
                     display:none;align-items:center;gap:10px;">
                    <i data-lucide="file-check" style="width:20px;height:20px;color:#16A34A;flex-shrink:0;"></i>
                    <div style="flex:1;">
                        <div style="font-size:0.8125rem;font-weight:600;color:#166534;" id="file-name">–</div>
                        <div style="font-size:0.75rem;color:#4ADE80;">Siap dikirim</div>
                    </div>
                    <button onclick="clearFile()" style="background:none;border:none;cursor:pointer;color:#64748B;">
                        <i data-lucide="x" style="width:16px;height:16px;"></i>
                    </button>
                </div>

                <button id="btn-submit" class="btn-brand"
                        style="width:100%;margin-top:16px;padding:12px;"
                        onclick="submitPayment()">
                    <i data-lucide="send" style="width:16px;height:16px;"></i>
                    Kirim Bukti Pembayaran
                </button>
            </div>
        </div>

        {{-- ─── Kanan: Ringkasan Booking ───────────────────────── --}}
        <div style="position:sticky;top:80px;">
            <div class="card" style="padding:20px;">
                <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                           color:#1E293B;margin:0 0 16px;">Ringkasan Booking</h3>

                <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
                    @foreach([
                        ['Lapangan',   'Futsal Planet Pekalongan'],
                        ['Jenis',      'Booking Biasa'],
                        ['Tanggal',    'Sabtu, 28 Agt 2026'],
                        ['Waktu',      '16:00 – 17:00 (1 jam)'],
                        ['Durasi',     '60 menit'],
                    ] as $row)
                    <div style="display:flex;justify-content:space-between;font-size:0.8125rem;gap:12px;">
                        <span style="color:#64748B;flex-shrink:0;">{{ $row[0] }}</span>
                        <span style="font-weight:600;color:#1E293B;text-align:right;">{{ $row[1] }}</span>
                    </div>
                    @endforeach
                </div>

                <div style="border-top:1px solid #F1F5F9;padding-top:14px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-weight:700;color:#1E293B;">Total Bayar</span>
                        <span style="font-family:'Poppins',sans-serif;font-size:1.25rem;font-weight:800;color:#166534;">
                            Rp 90.000
                        </span>
                    </div>
                </div>

                <div style="margin-top:14px;background:#FFFBEB;border:1px solid #FDE68A;
                            border-radius:6px;padding:10px 12px;font-size:0.75rem;color:#92400E;
                            display:flex;gap:7px;">
                    <i data-lucide="alert-triangle" style="width:14px;height:14px;flex-shrink:0;margin-top:1px;"></i>
                    <span>Booking ID: <strong>#SPTA-20260828-001</strong><br>
                    Cantumkan ID ini jika ada pertanyaan ke pemilik lapangan.</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /* Countdown timer 30 menit */
    let seconds = 29 * 60 + 58;
    const timerEl = document.getElementById('timer');

    const interval = setInterval(() => {
        if (seconds <= 0) {
            clearInterval(interval);
            timerEl.textContent = '00:00';
            timerEl.style.color = '#94A3B8';
            return;
        }
        seconds--;
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        timerEl.textContent = m + ':' + s;

        if (seconds < 300) timerEl.style.color = '#b91c1c'; // merah lebih gelap saat <5 menit
    }, 1000);

    /* Handle file upload preview */
    function handleFileSelect(input) {
        const file = input.files[0];
        if (!file) return;
        document.getElementById('file-name').textContent = file.name;
        document.getElementById('file-preview').style.display = 'flex';
        document.getElementById('upload-area').style.borderColor = '#16A34A';
        document.getElementById('upload-area').style.background  = '#F0FDF4';
    }

    function clearFile() {
        document.getElementById('file-input').value = '';
        document.getElementById('file-preview').style.display = 'none';
        document.getElementById('upload-area').style.borderColor = '#CBD5E1';
        document.getElementById('upload-area').style.background  = 'transparent';
    }

    function submitPayment() {
        const btn = document.getElementById('btn-submit');
        btn.innerHTML = '<span style="width:16px;height:16px;border:2px solid #fff;border-top-color:transparent;border-radius:50%;animation:spin 0.7s linear infinite;display:inline-block;"></span> Mengirim...';
        btn.disabled = true;
        setTimeout(() => {
            window.location.href = '/player/history';
        }, 1800);
    }
</script>
<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endpush

@endsection
