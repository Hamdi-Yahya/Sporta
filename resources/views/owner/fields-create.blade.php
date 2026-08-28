@extends('layouts.dashboard')

@section('title', 'Daftarkan Lapangan Baru')
@section('sidebar_role', 'Pemilik Lapangan')
@section('page_title', 'Daftarkan Lapangan Baru')

@section('sidebar_nav')
    @include('components.sidebar-owner')
@endsection

@section('content')

<div style="max-width:640px;">
    <div style="background:#fff;border:1px solid #E2E8F0;border-radius:8px;padding:28px;">

        {{-- Validation errors --}}
        @if($errors->any())
        <div style="background:#FEF2F2;border:1px solid #FECACA;padding:10px 14px;border-radius:6px;margin-bottom:16px;">
            <ul style="margin:0;padding:0 0 0 16px;font-size:0.8125rem;color:#DC2626;">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('owner.fields.store') }}" enctype="multipart/form-data"
              style="display:flex;flex-direction:column;gap:18px;">
            @csrf

            {{-- Nama Lapangan --}}
            <div>
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:6px;">Nama Lapangan</label>
                <input type="text" name="nama" value="{{ old('nama') }}" required
                       style="width:100%;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;"
                       placeholder="Contoh: GOR Pekalongan Court A">
            </div>

            {{-- Cabang Olahraga --}}
            <div>
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:6px;">Cabang Olahraga</label>
                <select name="cabor_id" required
                        style="width:100%;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;background:#fff;">
                    <option value="">— Pilih cabang olahraga —</option>
                    @foreach($caborList as $cabor)
                    <option value="{{ $cabor->id }}" {{ old('cabor_id') == $cabor->id ? 'selected' : '' }}>{{ $cabor->nama_cabor }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Lokasi --}}
            <div>
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:6px;">Lokasi</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" required
                       style="width:100%;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;"
                       placeholder="Jl. Contoh No. 123, Kota Pekalongan">
            </div>

            {{-- Deskripsi --}}
            <div>
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:6px;">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          style="width:100%;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;resize:vertical;"
                          placeholder="Deskripsi singkat lapangan...">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Fasilitas --}}
            <div>
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:6px;">Fasilitas</label>
                <textarea name="fasilitas" rows="2"
                          style="width:100%;padding:10px 14px;border:1px solid #E2E8F0;border-radius:6px;font-size:0.875rem;outline:none;resize:vertical;"
                          placeholder="Contoh: Parkir luas, Toilet, Ruang ganti, WiFi">{{ old('fasilitas') }}</textarea>
            </div>

            {{-- Foto --}}
            <div>
                <label style="display:block;font-size:0.8125rem;font-weight:600;color:#475569;margin-bottom:6px;">Foto Lapangan</label>
                <input type="file" name="foto" accept="image/*"
                       style="font-size:0.875rem;color:#64748B;">
            </div>

            {{-- Submit --}}
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" style="background:#16A34A;color:#fff;border:none;padding:10px 24px;border-radius:6px;font-weight:600;font-size:0.875rem;cursor:pointer;">
                    Daftarkan Lapangan
                </button>
                <a href="{{ route('owner.fields') }}" style="padding:10px 24px;border-radius:6px;font-size:0.875rem;color:#64748B;text-decoration:none;border:1px solid #E2E8F0;display:inline-flex;align-items:center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
