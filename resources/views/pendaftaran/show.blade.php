@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
    <a href="{{ route('pasien.pendaftaran.index') }}"
       class="text-sm font-medium text-oasis-blue hover:underline">
        &larr; Kembali ke Riwayat
    </a>

    <div class="mt-5 overflow-hidden rounded-xl border border-slate-200 bg-white">
        <div class="border-b border-slate-200 px-6 py-5 sm:px-8">
            <p class="text-sm text-slate-500">Detail Pendaftaran</p>

            <h1 class="mt-1 text-2xl font-bold text-slate-800">
                {{ $pendaftaran->kode_pendaftaran }}
            </h1>

            <p class="mt-2 text-sm text-slate-500">
                Informasi kunjungan rawat jalan
            </p>
        </div>

        <div class="grid gap-5 px-6 py-6 sm:grid-cols-2 sm:px-8">
            <div>
                <p class="text-sm text-slate-500">Tanggal Kunjungan</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pendaftaran->tanggal_kunjungan->format('d-m-Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Nomor Antrean</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pendaftaran->nomor_antrean }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Poli</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pendaftaran->poli->nama_poli ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Jenis Penjamin</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ ucfirst($pendaftaran->jenis_penjamin) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Nomor Peserta</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pendaftaran->nomor_peserta ?: '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Status Verifikasi</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ ucfirst($pendaftaran->status_verifikasi) }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-slate-500">Status Pendaftaran</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ ucfirst($pendaftaran->status) }}
                </p>
            </div>

            <div class="sm:col-span-2">
                <p class="text-sm text-slate-500">Keluhan Awal</p>
                <p class="mt-1 whitespace-pre-line text-slate-700">
                    {{ $pendaftaran->keluhan_awal ?: 'Tidak ada keluhan yang dicatat.' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection