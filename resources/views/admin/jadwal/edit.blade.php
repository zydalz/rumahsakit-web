@extends('layouts.app')

@section('title', 'Edit Jadwal Dokter - PT Oasis Husada')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-8">
    <h1 class="mb-2 text-2xl font-bold text-gray-800">Edit Jadwal Dokter</h1>
    <p class="mb-6 text-sm text-gray-500">Perbarui jadwal praktik dokter.</p>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="dokter_id" class="mb-2 block text-sm font-medium text-gray-700">Dokter & Poli <span class="text-red-500">*</span></label>
                <select id="dokter_id" name="dokter_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @foreach ($dokters as $dokter)
                        <option value="{{ $dokter->id }}" {{ old('dokter_id', $jadwal->dokter_id) == $dokter->id ? 'selected' : '' }}>
                            {{ $dokter->user->name ?? 'Dokter' }} - {{ $dokter->poli->nama_poli ?? 'Poli' }}
                        </option>
                    @endforeach
                </select>
                @error('dokter_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5">
                <label for="hari" class="mb-2 block text-sm font-medium text-gray-700">Hari Praktik <span class="text-red-500">*</span></label>
                <select id="hari" name="hari" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                        <option value="{{ $hari }}" {{ old('hari', $jadwal->hari) == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                    @endforeach
                </select>
                @error('hari') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="jam_mulai" class="mb-2 block text-sm font-medium text-gray-700">Jam Mulai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @error('jam_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="jam_selesai" class="mb-2 block text-sm font-medium text-gray-700">Jam Selesai <span class="text-red-500">*</span></label>
                    <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @error('jam_selesai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-5">
                <label for="kuota" class="mb-2 block text-sm font-medium text-gray-700">Kuota Pasien <span class="text-red-500">*</span></label>
                <input type="number" id="kuota" name="kuota" value="{{ old('kuota', $jadwal->kuota) }}" min="1" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                @error('kuota') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $jadwal->is_active ? '1' : '0') == '1' ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600">
                    Jadwal Berstatus Aktif
                </label>
                @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="rounded-lg bg-sky-700 px-5 py-2.5 font-medium text-white hover:bg-sky-800">Simpan Perubahan</button>
                <a href="{{ route('admin.jadwal.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-gray-700 hover:bg-gray-50">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection
