@extends('layouts.dashboard')

@section('title', 'Notifikasi')
@section('sidebar_role', Auth::user()->isOwner() ? 'Pemilik Lapangan' : 'Player')
@section('page_title', 'Notifikasi')

@section('sidebar_nav')
    @if(Auth::user()->isOwner())
        @include('components.sidebar-owner')
    @else
        @include('components.sidebar-player')
    @endif
@endsection

@section('content')

<style>
@media (max-width: 480px) {
    .notif-row { flex-direction: column !important; gap: 10px !important; }
    .notif-meta { flex-direction: column !important; gap: 4px !important; align-items: flex-start !important; }
}
</style>

<div style="background:#fff;border:1px solid #E2E8F0;border-radius:8px;overflow:hidden;">
    @forelse($notifikasis as $notif)
    <div class="notif-row" style="display:flex;align-items:flex-start;gap:12px;padding:14px 20px;border-bottom:1px solid #F1F5F9;
                {{ !$notif->status_baca ? 'background:#F0FDF4;' : '' }}">
        <div style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                    {{ !$notif->status_baca ? 'background:#16A34A;' : 'background:#E2E8F0;' }}">
            <i data-lucide="bell" style="width:16px;height:16px;color:{{ !$notif->status_baca ? '#fff' : '#94A3B8' }};"></i>
        </div>
        <div style="flex:1;min-width:0;">
            <div class="notif-meta" style="display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap;">
                <span style="font-weight:600;font-size:0.875rem;color:#1E293B;">{{ $notif->judul }}</span>
                <span style="font-size:0.6875rem;color:#94A3B8;white-space:nowrap;flex-shrink:0;">{{ $notif->created_at->diffForHumans() }}</span>
            </div>
            <p style="font-size:0.8125rem;color:#64748B;margin:4px 0 0;line-height:1.5;word-break:break-word;">{{ $notif->isi }}</p>
        </div>
        @unless($notif->status_baca)
        <form method="POST" action="{{ route('notifikasi.read', $notif) }}" style="flex-shrink:0;">
            @csrf
            <button type="submit" style="background:none;border:none;cursor:pointer;color:#16A34A;font-size:0.75rem;font-weight:600;white-space:nowrap;">
                Tandai dibaca
            </button>
        </form>
        @endunless
    </div>

    @empty
    <div style="padding:40px;text-align:center;color:#94A3B8;font-size:0.875rem;">
        Tidak ada notifikasi.
    </div>
    @endforelse

    @if($notifikasis->hasPages())
    <div style="padding:12px 20px;border-top:1px solid #E2E8F0;">
        {{ $notifikasis->links() }}
    </div>
    @endif
</div>

@endsection
