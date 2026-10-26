@extends('layouts.app')

@section('title', 'Edit Data Poli - PT Oasis Husada')

@section('content')
<div class="mx-auto max-w-3xl px-6 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Data Poli</h1>
        <p class="mt-1 text-sm text-gray-500">
            Perbarui informasi poliklinik {{ $poli->nama_poli }}.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.poli.update', $poli->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label for="nama_poli" class="mb-2 block text-sm font-medium text-gray-700">
                    Nama Poli <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="nama_poli"
                    name="nama_poli"
                    value="{{ old('nama_poli', $poli->nama_poli) }}"
                    required
                    maxlength="100"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none"
                >
                @error('nama_poli')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label for="deskripsi" class="mb-2 block text-sm font-medium text-gray-700">
                    Deskripsi
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-blue-500 focus:outline-none"
                >{{ old('deskripsi', $poli->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="flex items-center gap-2 text-sm text-gray-700">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        {{ old('is_active', $poli->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600"
                    >
                    Poliklinik Aktif Melayani Pasien
                </label>
                @error('is_active')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-wrap gap-3">
                <button
                    type="submit"
                    class="rounded-lg bg-sky-700 px-5 py-2.5 font-medium text-white hover:bg-sky-800"
                >
                    Simpan Perubahan
                </button>

                <a
                    href="{{ route('admin.poli.index') }}"
                    class="rounded-lg border border-gray-300 px-5 py-2.5 font-medium text-gray-700 hover:bg-gray-50"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
