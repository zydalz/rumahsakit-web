@extends('layouts.app')

@section('title', 'Dashboard Admin - PT Oasis Husada')
@section('header', 'Dashboard Administrator')

@section('content')
<div class="space-y-8 p-6 lg:p-8">

    {{-- Salam & Pengantar --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Ringkasan Rumah Sakit
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Pantau performa layanan, antrean pasien, dan ketersediaan obat secara terpusat.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pasien.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Data Pasien
            </a>

            <a href="{{ route('farmasi.obat.index') }}"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                Data Obat
            </a>
        </div>
    </div>

    {{-- Grid Kartu Statistik --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Total Pasien --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Total Pasien</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_pasien']) }}</p>
                <p class="mt-1 text-xs text-slate-400">Pasien terdaftar di sistem</p>
            </div>
        </div>

        {{-- Kunjungan Hari Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Kunjungan Hari Ini</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['kunjungan_hari_ini']) }}</p>
                <p class="mt-1 text-xs text-slate-400">Pendaftaran rawat jalan per {{ date('d M Y') }}</p>
            </div>
        </div>

        {{-- Obat Stok Kritis --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Obat Stok Kritis</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-rose-600">{{ number_format($stats['obat_stok_kritis']) }}</p>
                <p class="mt-1 text-xs text-slate-400">Stok &le; batas minimum</p>
            </div>
        </div>

        {{-- Total Penerimaan Kasir --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Akumulasi Kasir</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-slate-800">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">Total pembayaran berhasil tercatat</p>
            </div>
        </div>

    </div>

    {{-- Konten Grid: Pendaftaran Terbaru & Peringatan Stok --}}
    <div class="grid gap-8 lg:grid-cols-3">

        {{-- Kolom Kiri (2 Kolom): Pendaftaran Pasien Terbaru --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Kunjungan Pasien Terbaru
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Aktivitas registrasi dan antrean poli rawat jalan
                    </p>
                </div>

                <a href="{{ route('admin.pasien.index') }}" class="text-xs font-semibold text-sky-700 hover:text-sky-800">
                    Lihat Semua Pasien &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Kode / Pasien</th>
                            <th class="px-6 py-3">Poli / Dokter</th>
                            <th class="px-6 py-3">Tgl Kunjungan</th>
                            <th class="px-6 py-3">No. Antrean</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($pendaftaranTerbaru as $pendaftaran)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">{{ $pendaftaran->kode_pendaftaran }}</p>
                                    <p class="text-xs text-slate-500">{{ $pendaftaran->pasien?->nama ?? 'Pasien Anonim' }} ({{ $pendaftaran->pasien?->nomor_rm ?? '-' }})</p>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-800">{{ $pendaftaran->poli?->nama_poli ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $pendaftaran->jadwalDokter?->dokter?->user?->name ?? 'Dokter Ditugaskan' }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-slate-600">
                                    {{ $pendaftaran->tanggal_kunjungan ? $pendaftaran->tanggal_kunjungan->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-sky-50 font-bold text-sky-800">
                                        {{ $pendaftaran->nomor_antrean }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-medium
                                        {{ $pendaftaran->status === 'selesai' ? 'bg-emerald-50 text-emerald-700' : 'bg-sky-50 text-sky-700' }}">
                                        {{ ucfirst($pendaftaran->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-8 text-center text-sm text-slate-400">
                                    Belum ada data pendaftaran kunjungan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kolom Kanan (1 Kolom): Peringatan Stok Obat & Master Info --}}
        <div class="space-y-6">

            {{-- Kartu Peringatan Obat --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800">
                        Peringatan Stok Obat
                    </h2>
                    <span class="rounded-full bg-rose-50 px-2 py-0.5 text-xs font-bold text-rose-700">
                        {{ $stats['obat_stok_kritis'] }} Obat
                    </span>
                </div>

                <div class="mt-4 divide-y divide-slate-100">
                    @forelse($obatKritis as $obat)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $obat->nama_obat }}</p>
                                <p class="text-xs text-slate-400">Kode: {{ $obat->kode_obat }} | Min: {{ $obat->stok_minimum }} {{ $obat->satuan }}</p>
                            </div>
                            <span class="rounded-lg bg-rose-100 px-2.5 py-1 text-xs font-bold text-rose-800">
                                Sisa: {{ $obat->stok }}
                            </span>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-slate-400">
                            Semua stok obat dalam kondisi aman di atas batas minimum.
                        </p>
                    @endforelse
                </div>

                <div class="mt-4 border-t border-slate-100 pt-4">
                    <a href="{{ route('farmasi.obat.index') }}" class="block text-center text-xs font-semibold text-sky-700 hover:text-sky-800">
                        Kelola Stok di Farmasi &rarr;
                    </a>
                </div>
            </div>

            {{-- Ringkasan Sumber Daya Medis --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-base font-bold text-slate-800 border-b border-slate-100 pb-4">
                    Fasilitas & Tenaga Medis
                </h2>

                <div class="mt-4 space-y-3">
                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3">
                        <span class="text-sm text-slate-600">Dokter Spesialis & Umum</span>
                        <span class="font-bold text-slate-800">{{ $stats['total_dokter'] }} Dokter</span>
                    </div>

                    <div class="flex items-center justify-between rounded-xl bg-slate-50 p-3">
                        <span class="text-sm text-slate-600">Poliklinik Aktif</span>
                        <span class="font-bold text-slate-800">{{ $stats['total_poli'] }} Poli</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
