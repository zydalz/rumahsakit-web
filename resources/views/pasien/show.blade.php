
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
                    <dt class="text-sm text-slate-500">Nomor Telepon</dt>
                    <dd class="mt-1 font-medium text-slate-800">
                        {{ $pasien->no_telepon ?? '-' }}
                    </dd>
                </div>

                <div>
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
