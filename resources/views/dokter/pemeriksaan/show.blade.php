
@extends('layouts.app')

@section('title', 'Detail Rekam Medis')
@section('header', 'Detail Rekam Medis')

@section('content')
<div class="mx-auto max-w-4xl">

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-slate-800">Informasi Pasien</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-sm text-slate-500">Nama Pasien</p>
                <p class="font-medium text-slate-800">
                    {{ $pemeriksaan->pendaftaran->pasien->nama ?? $pemeriksaan->pendaftaran->pasien->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Nomor Rekam Medis</p>
                <p class="font-medium text-slate-800">
                    {{ $pemeriksaan->pendaftaran->pasien->nomor_rm ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Kode Pendaftaran</p>
                <p class="font-medium text-slate-800">
                    {{ $pemeriksaan->pendaftaran->kode_pendaftaran }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Tanggal Kunjungan</p>
                <p class="font-medium text-slate-800">
                    {{ $pemeriksaan->pendaftaran->tanggal_kunjungan
                        ? \Illuminate\Support\Carbon::parse($pemeriksaan->pendaftaran->tanggal_kunjungan)->format('d-m-Y')
                        : '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Dokter</p>
                <p class="font-medium text-slate-800">
                    {{ $pemeriksaan->dokter->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Poli</p>
                <p class="font-medium text-slate-800">
                    {{ $pemeriksaan->dokter->poli->nama_poli ?? '-' }}
                </p>
            </div>
        </div>

        <hr class="my-6 border-slate-200">

        <h2 class="mb-4 text-lg font-bold text-slate-800">Hasil Pemeriksaan</h2>

        <div class="space-y-5">
            <div>
                <p class="text-sm text-slate-500">Keluhan Utama</p>
                <p class="whitespace-pre-line text-slate-800">{{ $pemeriksaan->keluhan }}</p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <p class="text-sm text-slate-500">Tekanan Darah</p>
                    <p>{{ $pemeriksaan->tekanan_darah ? $pemeriksaan->tekanan_darah . ' mmHg' : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Suhu Tubuh</p>
                    <p>{{ $pemeriksaan->suhu !== null ? $pemeriksaan->suhu . ' °C' : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Berat Badan</p>
                    <p>{{ $pemeriksaan->berat_badan !== null ? $pemeriksaan->berat_badan . ' kg' : '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-slate-500">Tinggi Badan</p>
                    <p>{{ $pemeriksaan->tinggi_badan !== null ? $pemeriksaan->tinggi_badan . ' cm' : '-' }}</p>
                </div>
            </div>

            <div>
                <p class="text-sm text-slate-500">Diagnosis</p>
                <p class="whitespace-pre-line text-slate-800">{{ $pemeriksaan->diagnosis }}</p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Catatan Tambahan</p>
                <p class="whitespace-pre-line text-slate-800">{{ $pemeriksaan->catatan ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-8">
            <a href="{{ route('dokter.pemeriksaan.index') }}"
                class="inline-block rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white hover:bg-sky-800">
                Kembali ke Daftar Pemeriksaan
            </a>
        </div>
    </div>
</div>
@endsection
