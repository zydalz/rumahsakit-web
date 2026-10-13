@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-8">
    <h1 class="mb-2 text-2xl font-bold text-gray-800">Tambah Jadwal Dokter</h1>
    <p class="mb-6 text-sm text-gray-500">Atur hari dan jam praktik dokter.</p>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @if ($dokters->isEmpty())
            <p class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">
                Belum ada profil dokter aktif. Tambahkan profil dokter terlebih dahulu.
            </p>
            <a href="{{ route('admin.dokter.create') }}" class="mt-4 inline-block rounded-lg bg-[#17618B] px-5 py-2.5 text-white">
                Tambah Dokter
            </a>
        @else
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="dokter_id" class="mb-2 block text-sm font-medium text-gray-700">Dokter</label>
                    <select id="dokter_id" name="dokter_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                        <option value="">Pilih dokter</option>
                        @foreach ($dokters as $dokter)
                            <option value="{{ $dokter->id }}" {{ old('dokter_id') == $dokter->id ? 'selected' : '' }}>
                                {{ $dokter->user->name ?? 'Nama tidak tersedia' }} - {{ $dokter->poli->nama_poli ?? 'Poli tidak tersedia' }}
                            </option>
                        @endforeach
                    </select>
                    @error('dokter_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-5">
                    <label for="hari" class="mb-2 block text-sm font-medium text-gray-700">Hari Praktik</label>
                    <select id="hari" name="hari" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                        <option value="">Pilih hari</option>
                        @foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $hari)
                            <option value="{{ $hari }}" {{ old('hari') == $hari ? 'selected' : '' }}>{{ $hari }}</option>
                        @endforeach
                    </select>
                    @error('hari') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="jam_mulai" class="mb-2 block text-sm font-medium text-gray-700">Jam Mulai</label>
                        <input type="time" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                        @error('jam_mulai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="jam_selesai" class="mb-2 block text-sm font-medium text-gray-700">Jam Selesai</label>
                        <input type="time" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                        @error('jam_selesai') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label for="kuota" class="mb-2 block text-sm font-medium text-gray-700">Kuota Pasien</label>
                    <input type="number" id="kuota" name="kuota" value="{{ old('kuota', 20) }}" min="1" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @error('kuota') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        Jadwal aktif
                    </label>
                    @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-lg bg-[#17618B] px-5 py-2.5 font-medium text-white">Simpan Jadwal</button>
                    <a href="{{ route('admin.jadwal.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-gray-700">Kembali</a>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection