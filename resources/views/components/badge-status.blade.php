{{-- ─── Badge Status Booking (§3.7) ──────────────────────────────
     Props: $status — nilai: menunggu_pembayaran | menunggu_verifikasi |
            terkonfirmasi | ditolak | expired | selesai
─────────────────────────────────────────────────────────────── --}}
@props(['status' => 'menunggu_pembayaran'])

@php
    $config = match($status) {
        'menunggu_pembayaran'  => ['class' => 'badge-warning',  'icon' => 'clock',          'label' => 'Menunggu Pembayaran'],
        'menunggu_verifikasi'  => ['class' => 'badge-orange',   'icon' => 'file-search',     'label' => 'Menunggu Verifikasi'],
        'terkonfirmasi'        => ['class' => 'badge-success',  'icon' => 'check-circle',    'label' => 'Terkonfirmasi'],
        'ditolak'              => ['class' => 'badge-danger',   'icon' => 'x-circle',        'label' => 'Ditolak'],
        'expired'              => ['class' => 'badge-danger',   'icon' => 'timer-off',       'label' => 'Kedaluwarsa'],
        'selesai'              => ['class' => 'badge-gray',     'icon' => 'check-check',     'label' => 'Selesai'],
        default                => ['class' => 'badge-gray',     'icon' => 'help-circle',     'label' => ucfirst($status)],
    };
@endphp

<span class="badge {{ $config['class'] }}">
    <i data-lucide="{{ $config['icon'] }}" style="width:11px;height:11px;"></i>
    {{ $config['label'] }}
</span>
