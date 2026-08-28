@extends('layouts.dashboard')

@section('title', 'Approval Lapangan')
@section('sidebar_role', 'Administrator')
@section('page_title', 'Approval Lapangan')

@section('sidebar_nav')
@php $current = request()->route()->getName() ?? ''; @endphp
<ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
    <li>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ str_starts_with($current, 'admin.dashboard') ? 'active' : '' }}">
            <i data-lucide="layout-dashboard" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Beranda</span>
        </a>
    </li>
    <li>
        <a href="{{ route('admin.approval') }}"
           class="sidebar-link {{ str_starts_with($current, 'admin.approval') ? 'active' : '' }}">
            <i data-lucide="shield-check" style="width:17px;height:17px;flex-shrink:0;"></i>
            <span>Approval Lapangan</span>
        </a>
    </li>
</ul>
@endsection

@section('content')

{{-- Flash messages --}}
@if(session('success'))
<div style="background:#F0FDF4;border:1px solid #BBF7D0;padding:10px 14px;border-radius:6px;margin-bottom:16px;">
    <p style="font-size:0.8125rem;color:#166534;margin:0;">{{ session('success') }}</p>
</div>
@endif

<div style="background:#fff;border:1px solid #E2E8F0;border-radius:8px;overflow:hidden;">
    <table style="width:100%;border-collapse:collapse;font-size:0.875rem;">
        <thead>
            <tr style="background:#F8FAFC;border-bottom:1px solid #E2E8F0;">
                <th style="text-align:left;padding:12px 16px;font-weight:600;color:#475569;">Lapangan</th>
                <th style="text-align:left;padding:12px 16px;font-weight:600;color:#475569;">Cabor</th>
                <th style="text-align:left;padding:12px 16px;font-weight:600;color:#475569;">Owner</th>
                <th style="text-align:left;padding:12px 16px;font-weight:600;color:#475569;">Lokasi</th>
                <th style="text-align:center;padding:12px 16px;font-weight:600;color:#475569;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingLapangans as $lap)
            <tr style="border-bottom:1px solid #F1F5F9;">
                <td style="padding:12px 16px;font-weight:600;color:#1E293B;">{{ $lap->nama }}</td>
                <td style="padding:12px 16px;color:#64748B;">{{ $lap->cabangOlahraga->nama_cabor }}</td>
                <td style="padding:12px 16px;color:#64748B;">{{ $lap->owner->name }}</td>
                <td style="padding:12px 16px;color:#64748B;">{{ $lap->lokasi }}</td>
                <td style="padding:12px 16px;text-align:center;">
                    <div style="display:flex;gap:6px;justify-content:center;">
                        {{-- Approve --}}
                        <form method="POST" action="{{ route('admin.approval.approve', $lap) }}">
                            @csrf
                            <button type="submit" style="background:#16A34A;color:#fff;border:none;padding:6px 14px;border-radius:4px;font-size:0.8125rem;font-weight:600;cursor:pointer;">
                                Setujui
                            </button>
                        </form>
                        {{-- Reject --}}
                        <form method="POST" action="{{ route('admin.approval.reject', $lap) }}" onsubmit="return promptReject(this);">
                            @csrf
                            <input type="hidden" name="alasan_reject" class="reject-reason">
                            <button type="submit" style="background:#DC2626;color:#fff;border:none;padding:6px 14px;border-radius:4px;font-size:0.8125rem;font-weight:600;cursor:pointer;">
                                Tolak
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding:32px;text-align:center;color:#94A3B8;">Tidak ada pengajuan lapangan yang menunggu persetujuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($pendingLapangans->hasPages())
    <div style="padding:12px 16px;border-top:1px solid #E2E8F0;">
        {{ $pendingLapangans->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function promptReject(form) {
        const reason = prompt('Masukkan alasan penolakan:');
        if (!reason) return false;
        form.querySelector('.reject-reason').value = reason;
        return true;
    }
</script>
@endpush

@endsection
