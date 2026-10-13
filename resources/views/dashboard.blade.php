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
            Dashboard Pasien
        </p>

        <h1 class="mt-2 text-2xl font-bold text-slate-800 sm:text-3xl">
            Selamat datang, {{ $user->name }}!
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Kelola informasi dan kebutuhan layanan kesehatan Anda melalui halaman ini.
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

    {{-- Informasi Pasien --}}
    <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">
                    Informasi Pasien
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi dasar akun dan nomor rekam medis Anda.
                </p>
            </div>

            <span class="w-fit rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                Akun Pasien
            </span>
        </div>

        <div class="mt-6 grid gap-5 sm:grid-cols-2">
            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Nama Pasien</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pasien?->nama ?? $user->name }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Nomor Rekam Medis</p>
                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pasien?->nomor_rm ?? 'Belum tersedia' }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Email</p>
                <p class="mt-1 break-words font-semibold text-slate-800">
                    {{ $user->email }}
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4">
                <p class="text-sm text-slate-500">Status Profil</p>
                <p class="mt-1 font-semibold {{ $pasien ? 'text-green-700' : 'text-amber-700' }}">
                    {{ $pasien ? 'Profil tersedia' : 'Profil belum terhubung' }}
                </p>
            </div>
        </div>
    </div>

    {{-- Layanan --}}
    <div class="mb-4">
        <h2 class="text-lg font-semibold text-slate-800">
            Layanan Pasien
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Pilih layanan yang ingin Anda akses.
        </p>
    </div>

    <div class="grid gap-5 md:grid-cols-2">

        {{-- Pendaftaran --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 11h18M12 14v4M10 16h4"/>
                </svg>
            </div>

            <h3 class="mt-4 text-base font-semibold text-slate-800">
                Pendaftaran Rawat Jalan
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Daftarkan kunjungan, pilih poli dan jadwal dokter, serta lihat informasi pendaftaran Anda.
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

        {{-- Informasi akun --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-teal-50 text-teal-700">
                <svg xmlns="http://www.w3.org/2000/svg"
                     width="24" height="24" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor"
                     stroke-width="1.8" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M5 21v-2a7 7 0 0 1 14 0v2"/>
                </svg>
            </div>

            <h3 class="mt-4 text-base font-semibold text-slate-800">
                Informasi Akun
            </h3>

            <p class="mt-2 text-sm leading-6 text-slate-500">
                Pastikan Anda menggunakan akun yang benar saat mengakses layanan rumah sakit.
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

    {{-- Catatan --}}
    @unless ($pasien)
        <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
            <p class="text-sm leading-6 text-amber-800">
                Profil pasien belum terhubung dengan akun ini. Layanan pendaftaran belum dapat digunakan. Hubungi admin untuk memeriksa data akun Anda.
            </p>
        </div>
    @endunless

</div>
@endsection