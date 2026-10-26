@extends('layouts.public')

@section('title', 'Dashboard Pasien')

@section('content')
@php
    $user = auth()->user();
    $pasien = $user->pasien;
@endphp

<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="mb-8">
        <p class="text-sm font-medium text-sky-700">
            Dashboard Pasien &bull; PT Oasis Husada
        </p>

        <h1 class="mt-2 text-2xl font-bold text-slate-800 sm:text-3xl">
            Selamat datang, {{ $user->name }}!
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Pantau status antrean, riwayat pemeriksaan, serta tagihan layanan kesehatan Anda.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- Kunjungan Hari Ini (Jika Ada) --}}
    @if(!empty($kunjunganHariIni))
        <div class="mb-8 overflow-hidden rounded-2xl border-2 border-sky-300 bg-gradient-to-r from-sky-50 to-indigo-50 p-6 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <span class="inline-flex rounded-full bg-sky-700 px-3 py-1 text-xs font-semibold text-white">
                        Jadwal Kunjungan Hari Ini
                    </span>
                    <h2 class="mt-3 text-xl font-bold text-slate-800">
                        {{ $kunjunganHariIni->poli?->nama_poli ?? 'Poliklinik Rawat Jalan' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-600">
                        Dokter: {{ $kunjunganHariIni->jadwalDokter?->dokter?->user?->name ?? 'Dokter Ditugaskan' }}
                    </p>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Kode Registrasi: <span class="font-semibold text-slate-700">{{ $kunjunganHariIni->kode_pendaftaran }}</span>
                    </p>
                </div>

                <div class="flex items-center gap-4 rounded-xl bg-white p-4 shadow-sm border border-slate-200">
                    <div class="text-right">
                        <p class="text-xs font-medium text-slate-500">Nomor Antrean Anda</p>
                        <p class="text-xs text-slate-400">Status: {{ ucfirst($kunjunganHariIni->status) }}</p>
                    </div>
                    <span class="flex h-14 w-14 items-center justify-center rounded-xl bg-sky-700 text-2xl font-black text-white shadow-inner">
                        #{{ $kunjunganHariIni->nomor_antrean }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Informasi Pasien & Profil --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Informasi Pasien
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Data identitas rekam medis resmi Anda di PT Oasis Husada.
                </p>
            </div>

            <span class="w-fit rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                Akun Pasien
            </span>
        </div>

        <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500">Nama Pasien</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pasien?->nama ?? $user->name }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500">Nomor Rekam Medis</p>
                <p class="mt-1 font-semibold text-sky-700">
                    {{ $pasien?->nomor_rm ?? 'Belum terbit' }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500">Email Terdaftar</p>
                <p class="mt-1 break-words font-semibold text-slate-800">
                    {{ $user->email }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-xs text-slate-500">Status Profil Medis</p>
                <p class="mt-1 font-semibold {{ $pasien ? 'text-green-700' : 'text-amber-700' }}">
                    {{ $pasien ? 'Profil Lengkap' : 'Profil Belum Lengkap' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Riwayat Kunjungan & Tagihan Pasien (Jika Ada Data) --}}
    @if(!empty($riwayatKunjungan) && $riwayatKunjungan->isNotEmpty())
        <div class="mb-8 grid gap-8 lg:grid-cols-2">

            {{-- Riwayat Kunjungan & Medis Terakhir --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800">
                        Riwayat Kunjungan Terakhir
                    </h2>
                    @if (\Illuminate\Support\Facades\Route::has('pasien.pendaftaran.index'))
                        <a href="{{ route('pasien.pendaftaran.index') }}" class="text-xs font-semibold text-sky-700 hover:text-sky-800">
                            Semua Riwayat &rarr;
                        </a>
                    @endif
                </div>

                <div class="mt-4 divide-y divide-slate-100">
                    @foreach($riwayatKunjungan as $kunjungan)
                        <div class="py-3">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ $kunjungan->poli?->nama_poli ?? 'Poli' }}
                                </p>
                                <span class="text-xs text-slate-500">
                                    {{ $kunjungan->tanggal_kunjungan ? $kunjungan->tanggal_kunjungan->format('d M Y') : '-' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Dokter: {{ $kunjungan->pemeriksaan?->dokter?->user?->name ?? 'Belum ada catatan' }}
                            </p>
                            @if($kunjungan->pemeriksaan?->diagnosis)
                                <p class="mt-1 text-xs text-slate-600 bg-slate-50 rounded p-1.5 border border-slate-100">
                                    <span class="font-medium text-slate-700">Diagnosis:</span> {{ $kunjungan->pemeriksaan->diagnosis }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Status Tagihan Pasien --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                    <h2 class="text-base font-bold text-slate-800">
                        Status Tagihan Biaya Medis
                    </h2>
                    <span class="text-xs text-slate-400">Kasir Oasis Husada</span>
                </div>

                <div class="mt-4 divide-y divide-slate-100">
                    @forelse($tagihanPasien as $tagihan)
                        <div class="flex items-center justify-between py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $tagihan->nomor_tagihan }}</p>
                                <p class="text-xs text-slate-500">
                                    Total: Rp{{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $tagihan->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $tagihan->status === 'lunas' ? 'LUNAS' : 'Sisa Rp' . number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="py-6 text-center text-sm text-slate-400">
                            Belum ada riwayat tagihan biaya.
                        </p>
                    @endforelse
                </div>
            </div>

        </div>
    @endif

    {{-- Layanan Cepat Pasien --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-slate-800">
            Akses Layanan Pasien
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Pilih tindakan cepat untuk kebutuhan kunjungan Anda.
        </p>
    </div>

    <div class="grid gap-5 md:grid-cols-2">

        {{-- Pendaftaran Rawat Jalan --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 11h18M12 14v4M10 16h4"/>
                </svg>
            </div>

            <h3 class="mt-4 text-base font-semibold text-slate-800">
                Pendaftaran Rawat Jalan
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Daftarkan kunjungan baru, pilih poliklinik dan dokter, serta dapatkan nomor antrean otomatis.
            </p>

            <div class="mt-5 flex flex-wrap gap-3">
                @if ($pasien && \Illuminate\Support\Facades\Route::has('pasien.pendaftaran.create'))
                    <a href="{{ route('pasien.pendaftaran.create') }}"
                       class="inline-flex items-center justify-center rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-sky-800">
                        Buat Pendaftaran
                    </a>
                @endif

                @if (\Illuminate\Support\Facades\Route::has('pasien.pendaftaran.index'))
                    <a href="{{ route('pasien.pendaftaran.index') }}"
                       class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Riwayat Pendaftaran
                    </a>
                @endif
            </div>
        </div>

        {{-- Informasi Akun & Logout --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                </svg>
            </div>

            <h3 class="mt-4 text-base font-semibold text-slate-800">
                Akses Akun
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Kelola sesi akun Anda dan pastikan informasi keamanan selalu terjaga.
            </p>

            <div class="mt-5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Logout
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- Catatan Profil --}}
    @unless ($pasien)
        <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <p class="text-sm leading-6 text-amber-800">
                Profil pasien belum terhubung dengan akun ini. Layanan pendaftaran belum dapat digunakan. Hubungi admin untuk memeriksa data akun Anda.
            </p>
        </div>
    @endunless

</div>
@endsection