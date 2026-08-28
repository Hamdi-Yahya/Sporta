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

<div style="display:flex;gap:20px;height:calc(100vh - 140px);">

    {{-- Sidebar ruang chat --}}
    <div style="width:240px;flex-shrink:0;background:#fff;border:1px solid #E2E8F0;border-radius:8px;overflow-y:auto;">
        <div style="padding:14px 16px;border-bottom:1px solid #E2E8F0;">
            <span style="font-family:'Poppins',sans-serif;font-weight:700;font-size:0.875rem;color:#1E293B;">Ruang Chat</span>
        </div>
        @foreach($komunitasList as $k)
        <a href="{{ Auth::user()->isOwner() ? route('owner.community.show', $k) : route('player.community.show', $k) }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 16px;text-decoration:none;
                  {{ $k->id === $komunitas->id ? 'background:#F0FDF4;border-left:3px solid #16A34A;' : 'border-left:3px solid transparent;' }}">
            <i data-lucide="message-circle" style="width:16px;height:16px;color:{{ $k->id === $komunitas->id ? '#16A34A' : '#94A3B8' }};flex-shrink:0;"></i>
            <span style="font-size:0.8125rem;color:{{ $k->id === $komunitas->id ? '#166534' : '#475569' }};font-weight:{{ $k->id === $komunitas->id ? '600' : '400' }};">{{ $k->cabangOlahraga->nama_cabor }}</span>
        </a>
        @endforeach
    </div>

    {{-- Chat area --}}
    <div style="flex:1;display:flex;flex-direction:column;background:#fff;border:1px solid #E2E8F0;border-radius:8px;overflow:hidden;">

        {{-- Header --}}
        <div style="padding:14px 20px;border-bottom:1px solid #E2E8F0;display:flex;align-items:center;gap:10px;">
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
        <form method="POST" action="{{ Auth::user()->isOwner() ? route('owner.community.kirim', $komunitas) : route('player.community.kirim', $komunitas) }}"
              style="display:flex;gap:8px;padding:12px 20px;border-top:1px solid #E2E8F0;">
            @csrf
            <input type="text" name="isi_pesan" placeholder="Ketik pesan..." required autocomplete="off"
                   style="flex:1;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;">
            <button type="submit" style="background:#16A34A;color:#fff;border:none;padding:10px 20px;border-radius:6px;font-weight:600;font-size:0.875rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                <i data-lucide="send" style="width:16px;height:16px;"></i>
                Kirim
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll ke pesan terbaru
    const chatBox = document.getElementById('chat-messages');
    if (chatBox) chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush

@endsection
