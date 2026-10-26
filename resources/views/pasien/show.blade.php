
@extends('layouts.public')

@section('title', 'Detail Pasien - Oasis Husada')

@section('content')
<section class="min-h-screen bg-oasis-bg px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-4xl">
        <a href="{{ route('admin.pasien.index') }}"
           class="text-sm font-medium text-oasis-blue hover:underline">
            &larr; Kembali ke Data Pasien
        </a>

        <div class="mb-6 mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-oasis-navy">
                    Detail Pasien
                </h1>
                <p class="mt-1 text-sm text-slate-600">
                    Informasi data pasien PT Oasis Husada.
                </p>
            </div>

            <a href="{{ route('admin.pasien.edit', $pasien) }}"
               class="inline-flex items-center justify-center rounded-lg bg-oasis-blue px-5 py-3 text-sm font-semibold text-white hover:bg-oasis-navy">
                Edit Data
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <p class="text-sm text-slate-500">Nomor Rekam Medis</p>
                <p class="mt-1 text-xl font-bold text-oasis-blue">
                    {{ $pasien->nomor_rm }}
                </p>
            </div>

            <dl class="grid gap-5 p-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm text-slate-500">Nama Pasien</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->nama }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">NIK</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->nik ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Tanggal Lahir</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->tanggal_lahir?->format('d-m-Y') ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Jenis Kelamin</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ ['L' => 'Laki-laki', 'P' => 'Perempuan'][$pasien->jenis_kelamin] ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Golongan Darah</dt>
                    <dd class="mt-1 font-semibold text-slate-800">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                            {{ $pasien->golongan_darah ?: 'Tidak Tahu' }}
                        </span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Riwayat Alergi</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        @if($pasien->riwayat_alergi)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-200">
                                <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                {{ $pasien->riwayat_alergi }}
                            </span>
                        @else
                            <span class="text-slate-400 italic">Tidak ada alergi tercatat</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Kontak Darurat Keluarga</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->kontak_darurat ?? '-' }}
                        @if($pasien->hubungan_darurat)
                            <span class="text-xs text-slate-500 font-normal">({{ $pasien->hubungan_darurat }})</span>
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-sm text-slate-500">Nomor Telepon Pasien</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->no_telepon ?? '-' }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Tanggal Data Dibuat</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->created_at?->format('d-m-Y H:i') ?? '-' }}
                    </dd>
                </div>

                <div class="sm:col-span-2">
                    <dt class="text-sm text-slate-500">Alamat</dt>
                    <dd class="mt-1 whitespace-pre-line font-medium text-slate-800">
                        {{ $pasien->alamat ?? '-' }}
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</section>
@endsection
