@extends('layouts.app')

@section('title', 'Edit Data Dokter - PT Oasis Husada')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-8">
    <h1 class="mb-2 text-2xl font-bold text-gray-800">Edit Data Dokter</h1>
    <p class="mb-6 text-sm text-gray-500">Perbarui informasi dokter {{ $dokter->user->name ?? '' }}.</p>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.dokter.update', $dokter->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="mb-2 block text-sm font-medium text-gray-700">Nama Dokter</label>
                <input type="text" disabled value="{{ $dokter->user->name ?? '-' }} ({{ $dokter->user->email ?? '-' }})" class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-gray-600">
                <p class="mt-1 text-xs text-gray-400">Nama dan email akun dikelola melalui manajemen pengguna.</p>
            </div>

            <div class="mb-5">
                <label for="poli_id" class="mb-2 block text-sm font-medium text-gray-700">Poliklinik Penugasan <span class="text-red-500">*</span></label>
                <select id="poli_id" name="poli_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    <option value="">Pilih poliklinik</option>
                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}" {{ old('poli_id', $dokter->poli_id) == $poli->id ? 'selected' : '' }}>
                            {{ $poli->nama_poli }}
                        </option>
                    @endforeach
                </select>
                @error('poli_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="nomor_sip" class="mb-2 block text-sm font-medium text-gray-700">Nomor SIP (Surat Izin Praktik)</label>
                <input id="nomor_sip" name="nomor_sip" value="{{ old('nomor_sip', $dokter->nomor_sip) }}" maxlength="50" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                @error('nomor_sip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="no_telepon" class="mb-2 block text-sm font-medium text-gray-700">Nomor Telepon Kontak</label>
                <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $dokter->no_telepon) }}" maxlength="20" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                @error('no_telepon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $dokter->is_active ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    Dokter Berstatus Aktif Melayani
                </label>
                @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-sky-700 px-5 py-2.5 font-medium text-white hover:bg-sky-800">Simpan Perubahan</button>
                <a href="{{ route('admin.dokter.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-gray-700 hover:bg-gray-50">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
