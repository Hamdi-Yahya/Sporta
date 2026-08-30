@extends('layouts.dashboard')

@section('title', 'Komunitas Chat')
@section('sidebar_role', Auth::user()->isOwner() ? 'Pemilik Lapangan' : 'Player')
@section('page_title', 'Komunitas — ' . $komunitas->nama)

@section('sidebar_nav')
    @if(Auth::user()->isOwner())
        @include('components.sidebar-owner')
    @else
        @include('components.sidebar-player')
    @endif
@endsection

@section('content')

<style>
/* Responsive community chat */
@media (max-width: 768px) {
    .community-chat-layout {
        grid-template-columns: 1fr !important;
        height: auto !important;
        overflow: visible !important;
    }
    .community-chat-sidebar {
        border-right: none !important;
        border-bottom: 1px solid #E2E8F0;
        /* Jangan batasi tinggi di mobile - tampilkan semua cabor */
        width: 100% !important;
        height: auto !important;
        max-height: none !important;
        overflow-y: visible !important;
    }
    .community-chat-area {
        min-height: 70vh;
        height: 70vh !important;
    }
    .chat-back-btn {
        display: flex !important;
    }
}
@media (min-width: 769px) {
    .chat-back-btn { display: none !important; }
}
</style>

<div class="community-chat-layout" style="display:grid;grid-template-columns:300px 1fr;gap:0;height:calc(100vh - 140px);
            border:1px solid #E2E8F0;border-radius:10px;overflow:hidden;background:#fff;">

    <div class="community-chat-sidebar" style="width:300px;border-right:1px solid #E2E8F0;display:flex;flex-direction:column;background:#fff;border-top-left-radius:10px;border-bottom-left-radius:10px;">
        <div style="padding:14px 16px;border-bottom:1px solid #F1F5F9;">
            <h3 style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;
                       color:#1E293B;margin:0 0 10px;">Ruang Komunitas</h3>
            <form method="GET" action="{{ route(Auth::user()->isOwner() ? 'owner.community.show' : 'player.community.show', $komunitas->id) }}">
                <input type="text" name="search" class="form-input" style="font-size:0.8125rem;"
                       placeholder="Cari komunitas..." value="{{ request('search') }}">
            </form>
        </div>

        <div style="overflow-y:auto;flex:1;">
            @php $totalUsers = \App\Models\User::count(); @endphp
            @foreach($komunitasList as $k)
            @php 
                $cabor = $k->cabangOlahraga; 
                $isActive = $k->id === $komunitas->id;
                $bg = '#16A34A'; $icon = 'message-circle';
                if(str_contains(strtolower($cabor->nama_cabor), 'futsal')) { $bg = '#16A34A'; $icon = 'zap'; }
                elseif(str_contains(strtolower($cabor->nama_cabor), 'bulu tangkis')) { $bg = '#0891B2'; $icon = 'wind'; }
                elseif(str_contains(strtolower($cabor->nama_cabor), 'basket')) { $bg = '#EA580C'; $icon = 'circle-dot'; }
                elseif(str_contains(strtolower($cabor->nama_cabor), 'tenis')) { $bg = '#7C3AED'; $icon = 'target'; }
            @endphp
            <a href="{{ Auth::user()->isOwner() ? route('owner.community.show', $k) : route('player.community.show', $k) }}" 
                 style="display:block;text-decoration:none;padding:12px 14px;cursor:pointer;transition:background 0.15s;
                        {{ $isActive ? 'background:#F0FDF4;border-right:3px solid #16A34A;' : 'border-right:3px solid transparent;' }}"
                 onmouseover="{{ !$isActive ? 'this.style.background=\'#F8FAFC\'' : '' }}"
                 onmouseout="{{ !$isActive ? 'this.style.background=\'transparent\'' : '' }}">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:40px;height:40px;background:{{ $bg }};border-radius:10px;
                                display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i data-lucide="{{ $icon }}" style="width:18px;height:18px;color:#fff;"></i>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div style="display:flex;justify-content:space-between;align-items:center;gap:6px;">
                            <span style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;
                                         color:{{ $isActive ? '#166534' : '#1E293B' }};">
                                {{ $cabor->nama_cabor }}
                            </span>
                        </div>
                        <div style="font-size:0.75rem;color:#64748B;white-space:nowrap;overflow:hidden;
                                    text-overflow:ellipsis;margin-top:2px;">
                            {{ $k->deskripsi }}
                        </div>
                        <div style="font-size:0.7rem;color:#94A3B8;margin-top:2px;">
                            {{ number_format($totalUsers) }} anggota
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    {{-- Chat area --}}
    <div class="community-chat-area" style="display:flex;flex-direction:column;background:#fff;overflow:hidden;">

        {{-- Header --}}
        <div style="padding:14px 20px;border-bottom:1px solid #E2E8F0;display:flex;align-items:center;gap:10px;">
            {{-- Tombol back (mobile only) --}}
            <a href="{{ Auth::user()->isOwner() ? route('owner.community') : route('player.community') }}"
               class="chat-back-btn"
               style="display:none;align-items:center;justify-content:center;width:32px;height:32px;
                      background:#F1F5F9;border-radius:6px;text-decoration:none;color:#475569;flex-shrink:0;">
                <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
            </a>
            <i data-lucide="message-circle" style="width:20px;height:20px;color:#16A34A;"></i>
            <div>
                <div style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.9375rem;color:#1E293B;">{{ $komunitas->nama }}</div>
                <div style="font-size:0.75rem;color:#94A3B8;">{{ $komunitas->deskripsi }}</div>
            </div>
        </div>

        {{-- Messages --}}
        <div id="chat-messages" style="flex:1;overflow-y:auto;padding:16px 20px;display:flex;flex-direction:column;gap:8px;">
            @forelse($pesanList as $pesan)
            <div style="display:flex;flex-direction:column;{{ $pesan->user_id === Auth::id() ? 'align-items:flex-end;' : 'align-items:flex-start;' }}">
                @if($pesan->user_id !== Auth::id())
                <span style="font-size:0.6875rem;color:#64748B;margin-bottom:2px;font-weight:600;">{{ $pesan->user->name }}</span>
                @endif
                <div style="max-width:70%;padding:8px 14px;border-radius:{{ $pesan->user_id === Auth::id() ? '12px 12px 4px 12px' : '12px 12px 12px 4px' }};
                            {{ $pesan->user_id === Auth::id() ? 'background:#16A34A;color:#fff;' : 'background:#F1F5F9;color:#1E293B;' }}
                            font-size:0.875rem;line-height:1.5;">
                    {{ $pesan->isi_pesan }}
                </div>
                <span style="font-size:0.625rem;color:#94A3B8;margin-top:2px;">{{ $pesan->waktu_kirim->format('H:i') }}</span>
            </div>
            @empty
            <div style="text-align:center;padding:40px;color:#94A3B8;font-size:0.875rem;">
                Belum ada pesan. Mulai percakapan!
            </div>
            @endforelse
        </div>

        {{-- Input --}}
        {{-- Input --}}
        <form id="chat-form" method="POST" action="{{ Auth::user()->isOwner() ? route('owner.community.kirim', $komunitas) : route('player.community.kirim', $komunitas) }}"
              style="display:flex;gap:8px;padding:12px 20px;border-top:1px solid #E2E8F0;">
            @csrf
            <input type="text" id="chat-input" name="isi_pesan" placeholder="Ketik pesan..." required autocomplete="off"
                   style="flex:1;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;">
            <button type="submit" id="chat-submit" style="background:#16A34A;color:#fff;border:none;padding:10px 20px;border-radius:6px;font-weight:600;font-size:0.875rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i data-lucide="send" style="width:16px;height:16px;"></i>
                Kirim
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Konfigurasi ID user aktif
    const currentUserId = {{ Auth::id() }};
    const komunitasId = {{ $komunitas->id }};

    // Auto-scroll ke pesan terbaru
    const chatBox = document.getElementById('chat-messages');
    function scrollToBottom() {
        if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
    }
    scrollToBottom();

    // Setup Laravel Echo listener
    if (window.Echo) {
        window.Echo.channel(`komunitas.${komunitasId}`)
            .listen('PesanTerkirim', (e) => {
                // Jangan tambah bubble dari Echo jika pesan dari diri sendiri
                // (karena diri sendiri sudah ditambahkan saat form sukses dikirim)
                if (e.user_id !== currentUserId) {
                    appendMessage(e, false);
                }
            });
    }

    // Tangani pengiriman form via AJAX
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const submitBtn = document.getElementById('chat-submit');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const pesan = input.value.trim();
        if (!pesan) return;

        // Kosongkan input dan matikan tombol sementara
        input.value = '';
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.7';

        try {
            const formData = new FormData(form);
            formData.set('isi_pesan', pesan);

            const res = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            
            // Tambah bubble chat diri sendiri segera setelah berhasil
            if (data.pesan) {
                appendMessage({
                    user_id: data.pesan.user_id,
                    isi_pesan: data.pesan.isi_pesan,
                    waktu_kirim: new Date(data.pesan.waktu_kirim).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}),
                    user: data.pesan.user
                }, true);
            }
        } catch (error) {
            console.error('Gagal mengirim pesan:', error);
            input.value = pesan; // kembalikan teks jika gagal
        } finally {
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            input.focus();
        }
    });

    // Fungsi helper untuk menambah bubble ke DOM
    function appendMessage(msg, isMine) {
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.flexDirection = 'column';
        div.style.alignItems = isMine ? 'flex-end' : 'flex-start';

        let nameHtml = '';
        if (!isMine) {
            nameHtml = `<span style="font-size:0.6875rem;color:#64748B;margin-bottom:2px;font-weight:600;">${msg.user.name}</span>`;
        }

        const bg = isMine ? '#16A34A' : '#F1F5F9';
        const color = isMine ? '#fff' : '#1E293B';
        const radius = isMine ? '12px 12px 4px 12px' : '12px 12px 12px 4px';

        div.innerHTML = `
            ${nameHtml}
            <div style="max-width:70%;padding:8px 14px;border-radius:${radius};background:${bg};color:${color};font-size:0.875rem;line-height:1.5;">
                ${msg.isi_pesan}
            </div>
            <span style="font-size:0.625rem;color:#94A3B8;margin-top:2px;">${msg.waktu_kirim}</span>
        `;
        
        // Hapus teks "Belum ada pesan" jika ada
        const emptyMsg = chatBox.querySelector('div[style*="Belum ada pesan"]');
        if (emptyMsg) emptyMsg.remove();

        chatBox.appendChild(div);
        scrollToBottom();
    }
</script>
@endpush

@endsection
