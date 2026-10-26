
@extends('layouts.public')

@section('title', 'Edit Pasien - Oasis Husada')

@section('content')
<section class="min-h-screen bg-oasis-bg px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('admin.pasien.index') }}"
           class="text-sm font-medium text-oasis-blue hover:underline">
            &larr; Kembali ke Data Pasien
        </a>

        <div class="mb-6 mt-4">
            <h1 class="text-2xl font-bold text-oasis-navy">
                Edit Data Pasien
            </h1>
            <p class="mt-2 text-sm text-slate-600">
                Perbarui informasi pasien sesuai data terbaru.
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @if ($errors->any())
                <div class="mb-6">
                    <x-alert type="error">
                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('admin.pasien.update', $pasien) }}"
                  class="space-y-5">
                @csrf
                @method('PUT')

                
                <div>  
                <div>
                    <label for="nama" class="mb-1 block text-sm font-medium text-oasis-navy">
                        Nama Lengkap
                    </label>

                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $pasien->nama) }}"
                        maxlength="100"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5"
                    >

                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                </div>


                <div>
                    <label for="nomor_rm" class="mb-2 block text-sm font-medium text-slate-700">
                        Nomor Rekam Medis <span class="text-red-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="nomor_rm"
                        name="nomor_rm"
                        value="{{ old('nomor_rm', $pasien->nomor_rm) }}"
                        maxlength="20"
                        required
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >
                    @error('nomor_rm')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nik" class="mb-2 block text-sm font-medium text-slate-700">
                        NIK
                    </label>
                    <input
                        type="text"
                        id="nik"
                        name="nik"
                        value="{{ old('nik', $pasien->nik) }}"
                        maxlength="16"
                        inputmode="numeric"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >
                    @error('nik')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="mb-2 block text-sm font-medium text-slate-700">
                        Tanggal Lahir
                    </label>
                    <input
                        type="date"
                        id="tanggal_lahir"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir', $pasien->tanggal_lahir?->format('Y-m-d')) }}"
                        max="{{ now()->toDateString() }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >
                    @error('tanggal_lahir')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="mb-2 block text-sm font-medium text-slate-700">
                        Jenis Kelamin
                    </label>
                    <select
                        id="jenis_kelamin"
                        name="jenis_kelamin"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >
                        <option value="">Pilih jenis kelamin</option>
                        <option value="L" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'L')>
                            Laki-laki
                        </option>
                        <option value="P" @selected(old('jenis_kelamin', $pasien->jenis_kelamin) === 'P')>
                            Perempuan
                        </option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="golongan_darah" class="mb-2 block text-sm font-medium text-slate-700">
                        Golongan Darah
                    </label>
                    <select
                        id="golongan_darah"
                        name="golongan_darah"
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >
                        <option value="">Pilih golongan darah</option>
                        @foreach(['A', 'B', 'AB', 'O', 'Tidak Tahu'] as $goldar)
                            <option value="{{ $goldar }}" @selected(old('golongan_darah', $pasien->golongan_darah) === $goldar)>{{ $goldar }}</option>
                        @endforeach
                    </select>
                    @error('golongan_darah')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="riwayat_alergi" class="mb-2 block text-sm font-medium text-slate-700">
                        Riwayat Alergi (Obat / Makanan)
                    </label>
                    <input
                        type="text"
                        id="riwayat_alergi"
                        name="riwayat_alergi"
                        value="{{ old('riwayat_alergi', $pasien->riwayat_alergi) }}"
                        maxlength="1000"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                        placeholder="Contoh: Alergi Amoxicillin, Paracetamol (opsional)"
                    >
                    @error('riwayat_alergi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="kontak_darurat" class="mb-2 block text-sm font-medium text-slate-700">
                            Nomor Kontak Darurat
                        </label>
                        <input
                            type="tel"
                            id="kontak_darurat"
                            name="kontak_darurat"
                            value="{{ old('kontak_darurat', $pasien->kontak_darurat) }}"
                            maxlength="25"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                            placeholder="Contoh: 081299887766"
                        >
                        @error('kontak_darurat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="hubungan_darurat" class="mb-2 block text-sm font-medium text-slate-700">
                            Hubungan Kontak Darurat
                        </label>
                        <input
                            type="text"
                            id="hubungan_darurat"
                            name="hubungan_darurat"
                            value="{{ old('hubungan_darurat', $pasien->hubungan_darurat) }}"
                            maxlength="50"
                            class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                            placeholder="Contoh: Orang Tua, Suami, Istri"
                        >
                        @error('hubungan_darurat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="no_telepon" class="mb-2 block text-sm font-medium text-slate-700">
                        Nomor Telepon Pasien
                    </label>
                    <input
                        type="tel"
                        id="no_telepon"
                        name="no_telepon"
                        value="{{ old('no_telepon', $pasien->no_telepon) }}"
                        maxlength="20"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >
                    @error('no_telepon')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alamat" class="mb-2 block text-sm font-medium text-slate-700">
                        Alamat
                    </label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-oasis-blue/20"
                    >{{ old('alamat', $pasien->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                    <a href="{{ route('admin.pasien.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                        Batal
                    </a>

                    <x-button type="submit">
                        Simpan Perubahan
                    </x-button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
