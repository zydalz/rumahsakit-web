@extends('layouts.app')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-8">
    <h1 class="mb-2 text-2xl font-bold text-gray-800">Tambah Dokter</h1>
    <p class="mb-6 text-sm text-gray-500">Hubungkan akun dokter dengan poli tempat praktik.</p>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @if ($users->isEmpty())
            <div class="rounded-lg bg-amber-50 p-4 text-sm text-amber-800">
                Belum ada akun dengan role dokter yang belum memiliki profil.
                Buat akun dokter terlebih dahulu, atau periksa data dokter yang sudah ada.
            </div>
        @else
            <form action="{{ route('admin.dokter.store') }}" method="POST">
                @csrf

                <div class="mb-5">
                    <label for="user_id" class="mb-2 block text-sm font-medium text-gray-700">Akun Dokter</label>
                    <select id="user_id" name="user_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                        <option value="">Pilih akun dokter</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-5">
                    <label for="poli_id" class="mb-2 block text-sm font-medium text-gray-700">Poli</label>
                    <select id="poli_id" name="poli_id" required class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                        <option value="">Pilih poli</option>
                        @foreach ($polis as $poli)
                            <option value="{{ $poli->id }}" {{ old('poli_id') == $poli->id ? 'selected' : '' }}>
                                {{ $poli->nama_poli }}
                            </option>
                        @endforeach
                    </select>
                    @error('poli_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-5">
                    <label for="nomor_sip" class="mb-2 block text-sm font-medium text-gray-700">Nomor SIP (opsional)</label>
                    <input id="nomor_sip" name="nomor_sip" value="{{ old('nomor_sip') }}" maxlength="50" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @error('nomor_sip') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-5">
                    <label for="no_telepon" class="mb-2 block text-sm font-medium text-gray-700">Nomor Telepon (opsional)</label>
                    <input id="no_telepon" name="no_telepon" value="{{ old('no_telepon') }}" maxlength="20" class="w-full rounded-lg border border-gray-300 px-4 py-2.5">
                    @error('no_telepon') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-700">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        Dokter aktif
                    </label>
                    @error('is_active') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="rounded-lg bg-[#17618B] px-5 py-2.5 font-medium text-white">Simpan Dokter</button>
                    <a href="{{ route('admin.dokter.index') }}" class="rounded-lg border border-gray-300 px-5 py-2.5 text-gray-700">Kembali</a>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection