@extends('layouts.dashboard')

@section('title', 'Komunitas Olahraga')
@section('page_title', 'Komunitas')
@section('sidebar_role', 'Player')
@section('user_initial', 'A')

@section('sidebar_nav')
    @include('components.sidebar-player')
@endsection

@section('content')

@php
/* 7 cabor komunitas — Lari hanya komunitas (§1.3.1) */
$communities = [
    ['id'=>1,  'name'=>'Futsal',       'members'=>321, 'icon'=>'zap',         'bg'=>'#16A34A', 'lastMsg'=>'Siapa mau main besok pagi? Kumpul jam 6!', 'time'=>'5 mnt lalu'],
    ['id'=>2,  'name'=>'Bulu Tangkis', 'members'=>187, 'icon'=>'wind',        'bg'=>'#0891B2', 'lastMsg'=>'GOR Arinda buka slot open match jam 10 nih', 'time'=>'12 mnt lalu'],
    ['id'=>3,  'name'=>'Basket',       'members'=>214, 'icon'=>'circle-dot',  'bg'=>'#EA580C', 'lastMsg'=>'Latihan rutin setiap Selasa & Kamis jam 4 sore', 'time'=>'1 jam lalu'],
    ['id'=>4,  'name'=>'Tenis',        'members'=>98,  'icon'=>'target',      'bg'=>'#7C3AED', 'lastMsg'=>'Ada yang mau sparring Minggu pagi?', 'time'=>'2 jam lalu'],
    ['id'=>5,  'name'=>'Padel',        'members'=>73,  'icon'=>'activity',    'bg'=>'#0F766E', 'lastMsg'=>'Padel court baru di utara sudah buka!', 'time'=>'3 jam lalu'],
    ['id'=>6,  'name'=>'Mini Soccer',  'members'=>156, 'icon'=>'flag',        'bg'=>'#B45309', 'lastMsg'=>'Turnamen mini soccer open bulan September!', 'time'=>'kemarin'],
    ['id'=>7,  'name'=>'Lari',         'members'=>289, 'icon'=>'footprints',  'bg'=>'#DC2626', 'lastMsg'=>'Sunday morning run! Kumpul Alun-alun jam 5:30', 'time'=>'kemarin'],
];

/* Dummy chat messages untuk Futsal (active room) */
$messages = [
    ['user'=>'Rizky M.',   'self'=>false, 'text'=>'Halo semua! Ada yang mau main futsal besok Sabtu sore?', 'time'=>'15:20'],
    ['user'=>'Dinda R.',   'self'=>false, 'text'=>'Boleh! Jam berapa rencananya? Aku bisa ab 15.00', 'time'=>'15:22'],
    ['user'=>'Kamu',       'self'=>true,  'text'=>'Ikut dong! Lapangan mana rencananya?', 'time'=>'15:23'],
    ['user'=>'Rizky M.',   'self'=>false, 'text'=>'Rencana di Futsal Planet, aku udah cek ada slot jam 16:00-17:00 tersedia', 'time'=>'15:25'],
    ['user'=>'Bima S.',    'self'=>false, 'text'=>'Gas! Aku ajak 2 orang lagi ya biar genap 10', 'time'=>'15:26'],
    ['user'=>'Kamu',       'self'=>true,  'text'=>'Siap, nanti aku booking via SPORTA ya biar gampang', 'time'=>'15:27'],
    ['user'=>'Dinda R.',   'self'=>false, 'text'=>'Oke mantap! Ditunggu konfirmasinya 👍', 'time'=>'15:28'],
    ['user'=>'Rizky M.',   'self'=>false, 'text'=>'Kalau ada yang belum download SPORTA, coba booking online lebih praktis!', 'time'=>'15:30'],
];

$activeRoom = $communities[0]; // Futsal active
@endphp

{{-- ─── Layout: daftar komunitas (kiri) + chat (kanan) ──────── --}}
<div style="display:grid;grid-template-columns:300px 1fr;gap:0;height:calc(100vh - 140px);
            min-height:500px;border:1px solid #E2E8F0;border-radius:10px;overflow:hidden;background:#fff;">

    {{-- ─── Sidebar Daftar Komunitas ────────────────────────── --}}
    <div style="border-right:1px solid #E2E8F0;display:flex;flex-direction:column;">

        <div style="padding:14px 16px;border-bottom:1px solid #F1F5F9;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 10px;">Ruang Komunitas</h3>
            <input type="text" class="form-input" style="font-size:0.8125rem;"
                   placeholder="Cari komunitas...">
        </div>

        <div style="overflow-y:auto;flex:1;">
            @foreach($communities as $com)
            @php $isActive = $com['id'] === $activeRoom['id']; @endphp
            <div style="padding:12px 14px;cursor:pointer;transition:background 0.15s;
                        {{ $isActive ? 'background:#F0FDF4;border-right:3px solid #16A34A;' : 'border-right:3px solid transparent;' }}"
                 onmouseover="{{ !$isActive ? 'this.style.background=\'#F8FAFC\'' : '' }}"
                 onmouseout="{{ !$isActive ? 'this.style.background=\'transparent\'' : '' }}">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:40px;height:40px;background:{{ $com['bg'] }};border-radius:10px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $com['icon'] }}" style="width:18px;height:18px;color:#fff;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:6px;">
                            <span style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;
                                         color:{{ $isActive ? '#166534' : '#1E293B' }};">
                                {{ $com['name'] }}
                            </span>
                            <span style="font-size:0.7rem;color:#94A3B8;flex-shrink:0;">{{ $com['time'] }}</span>
                        </div>
                        <div style="font-size:0.75rem;color:#64748B;white-space:nowrap;overflow:hidden;
                                    text-overflow:ellipsis;margin-top:2px;">
                            {{ $com['lastMsg'] }}
                        </div>
                        <div style="font-size:0.7rem;color:#94A3B8;margin-top:2px;">
                            {{ number_format($com['members']) }} anggota
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ─── Area Chat ─────────────────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;">

        {{-- Chat header --}}
        <div style="padding:14px 18px;border-bottom:1px solid #F1F5F9;
                    display:flex;align-items:center;gap:12px;">
            <div style="width:38px;height:38px;background:{{ $activeRoom['bg'] }};border-radius:9px;
                        display:flex;align-items:center;justify-content:center;">
                <i data-lucide="{{ $activeRoom['icon'] }}" style="width:17px;height:17px;color:#fff;"></i>
            </div>
            <div>
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;">
                    Komunitas {{ $activeRoom['name'] }}
                </div>
                <div style="font-size:0.75rem;color:#64748B;">{{ number_format($activeRoom['members']) }} anggota • Chat real-time</div>
            </div>
            <div style="margin-left:auto;display:flex;align-items:center;gap:4px;">
                <span style="width:8px;height:8px;background:#16A34A;border-radius:50%;"></span>
                <span style="font-size:0.75rem;color:#64748B;">Online</span>
            </div>
        </div>

        {{-- Messages area (FR-F3, FR-F4, FR-F5) --}}
        <div id="chat-messages"
             style="flex:1;overflow-y:auto;padding:16px 18px;display:flex;flex-direction:column;gap:12px;
                    background:#F8FAFC;">

            {{-- Date divider --}}
            <div style="text-align:center;">
                <span style="background:#fff;border:1px solid #E2E8F0;border-radius:20px;
                             padding:4px 12px;font-size:0.75rem;color:#94A3B8;">
                    Hari ini, 27 Agustus 2026
                </span>
            </div>

            @foreach($messages as $msg)
            <div style="display:flex;{{ $msg['self'] ? 'justify-content:flex-end;' : 'justify-content:flex-start;' }}">
                <div style="max-width:72%;">

                    @if(!$msg['self'])
                    {{-- Sender name (FR-F5) --}}
                    <div style="font-size:0.75rem;font-weight:600;color:#16A34A;margin-bottom:3px;margin-left:4px;">
                        {{ $msg['user'] }}
                    </div>
                    @endif

                    <div style="display:flex;align-items:flex-end;gap:5px;{{ $msg['self'] ? 'flex-direction:row-reverse;' : '' }}">
                        @if(!$msg['self'])
                        {{-- Avatar --}}
                        <div style="width:26px;height:26px;border-radius:50%;background:#CBD5E1;
                                    display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <span style="font-size:0.625rem;font-weight:700;color:#475569;">
                                {{ substr($msg['user'],0,1) }}
                            </span>
                        </div>
                        @endif

                        <div class="{{ $msg['self'] ? 'chat-bubble-own' : 'chat-bubble-other' }}">
                            {{ $msg['text'] }}
                        </div>
                    </div>

                    {{-- Time (FR-F5) --}}
                    <div style="font-size:0.6875rem;color:#94A3B8;margin-top:3px;
                                {{ $msg['self'] ? 'text-align:right;margin-right:4px;' : 'margin-left:32px;' }}">
                        {{ $msg['time'] }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- ─── Input pesan (FR-F3) ─────────────────────────── --}}
        <div style="padding:12px 16px;border-top:1px solid #E2E8F0;background:#fff;">
            <div style="display:flex;align-items:center;gap:10px;">
                <input type="text" id="msg-input" class="form-input"
                       placeholder="Ketik pesan ke komunitas {{ $activeRoom['name'] }}..."
                       style="flex:1;"
                       onkeypress="if(event.key==='Enter'){sendMessage();}">
                <button onclick="sendMessage()" class="btn-brand" style="padding:10px 16px;flex-shrink:0;">
                    <i data-lucide="send" style="width:16px;height:16px;"></i>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    /* Scroll ke bawah saat load */
    const chatEl = document.getElementById('chat-messages');
    chatEl.scrollTop = chatEl.scrollHeight;

    /* Kirim pesan dummy (simulasi real-time) */
    function sendMessage() {
        const input = document.getElementById('msg-input');
        const text = input.value.trim();
        if (!text) return;

        const now = new Date();
        const time = now.getHours().toString().padStart(2,'0') + ':' + now.getMinutes().toString().padStart(2,'0');

        const wrap = document.createElement('div');
        wrap.style.display = 'flex';
        wrap.style.justifyContent = 'flex-end';
        wrap.innerHTML = `
            <div style="max-width:72%;">
                <div style="display:flex;align-items:flex-end;gap:5px;flex-direction:row-reverse;">
                    <div class="chat-bubble-own">${escapeHtml(text)}</div>
                </div>
                <div style="font-size:0.6875rem;color:#94A3B8;margin-top:3px;text-align:right;margin-right:4px;">${time}</div>
            </div>`;

        chatEl.appendChild(wrap);
        chatEl.scrollTop = chatEl.scrollHeight;
        input.value = '';
        lucide.createIcons();
    }

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }
</script>
@endpush

@endsection
