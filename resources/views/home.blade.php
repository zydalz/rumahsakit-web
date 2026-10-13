@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
    {{-- Hero --}}
    <section class="bg-oasis-bg">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-6 py-16 md:grid-cols-2 md:py-24">
            <div>
                <span class="inline-flex rounded-full bg-white px-4 py-2 text-sm font-medium text-oasis-teal shadow-sm">
                    Pelayanan Kesehatan Terpadu
                </span>

                <h1 class="mt-6 text-4xl font-bold leading-tight text-oasis-navy md:text-5xl">
                    Sehat Bersama
                    <span class="text-oasis-blue">Oasis Husada</span>
                </h1>

                <p class="mt-5 max-w-xl leading-7 text-slate-600">
                    Kami hadir untuk mendukung kebutuhan kesehatan Anda
                    melalui pelayanan yang ramah, informatif, dan mudah diakses.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-oasis-blue px-6 py-3 font-semibold text-white transition hover:bg-oasis-navy">
                        Daftar Online
                    </a>

                    <a href="#layanan"
                       class="rounded-lg border border-oasis-blue bg-white px-6 py-3 font-semibold text-oasis-blue transition hover:bg-slate-50">
                        Lihat Layanan
                    </a>
                </div>
            </div>

            <div class="relative">
                <img
                    src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=1200&q=85"
                    alt="Dokter memberikan pelayanan kesehatan"
                    class="h-72 w-full rounded-2xl object-cover shadow-lg md:h-[420px]"
                >
                <div class="absolute -bottom-5 left-5 rounded-xl bg-white p-4 shadow-md md:left-8">
                    <p class="font-semibold text-oasis-navy">Pelayanan Berorientasi Pasien</p>
                    <p class="mt-1 text-sm text-slate-500">Mengutamakan kenyamanan dan kebutuhan Anda</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Keunggulan --}}
    <section class="mx-auto max-w-7xl px-6 py-16">
        <div class="max-w-2xl">
            <p class="font-semibold text-oasis-teal">Mengapa Oasis Husada?</p>
            <h2 class="mt-2 text-3xl font-bold text-oasis-navy">
                Informasi kesehatan dalam satu tempat
            </h2>
            <p class="mt-4 leading-7 text-slate-600">
                Temukan informasi layanan dan akses pendaftaran dengan lebih praktis
                melalui sistem informasi rumah sakit.
            </p>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-oasis-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8">
                        <path d="M3 21h18M5 21V7l7-4 7 4v14M9 10h.01M15 10h.01M9 14h.01M15 14h.01M10 21v-3h4v3"/>
                    </svg>
                </div>
                <h3 class="mt-5 font-semibold text-oasis-navy">Layanan Terpadu</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Informasi poliklinik, pemeriksaan, dan layanan farmasi tersedia
                    dalam satu sistem.
                </p>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-teal-50 text-oasis-teal">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8">
                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>
                    </svg>
                </div>
                <h3 class="mt-5 font-semibold text-oasis-navy">Pendaftaran Online</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Pasien dapat memulai proses pendaftaran kunjungan tanpa harus
                    memulai dari halaman administrasi.
                </p>
            </article>

            <article class="rounded-xl border border-slate-200 bg-white p-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-50 text-oasis-blue">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.8">
                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                        <path d="M8 8h8M8 12h8M8 16h5"/>
                    </svg>
                </div>
                <h3 class="mt-5 font-semibold text-oasis-navy">Informasi Terorganisir</h3>
                <p class="mt-2 text-sm leading-6 text-slate-600">
                    Informasi kunjungan dan layanan pasien dikelola melalui
                    sistem sesuai hak akses pengguna.
                </p>
            </article>
        </div>
    </section>

    {{-- Layanan --}}
    <section id="layanan" class="bg-oasis-bg">
        <div class="mx-auto max-w-7xl px-6 py-16">
            <div class="text-center">
                <p class="font-semibold text-oasis-teal">Layanan Kami</p>
                <h2 class="mt-2 text-3xl font-bold text-oasis-navy">
                    Temukan layanan yang Anda butuhkan
                </h2>
                <p class="mx-auto mt-4 max-w-2xl leading-7 text-slate-600">
                    Berikut beberapa kategori layanan yang direncanakan dalam
                    sistem informasi PT Oasis Husada.
                </p>
            </div>

            <div class="mt-9 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    ['Poliklinik', 'Informasi poli dan jadwal dokter.'],
                    ['Laboratorium', 'Informasi pemeriksaan penunjang.'],
                    ['Farmasi', 'Pengelolaan resep dan informasi obat.'],
                    ['Instalasi Gawat Darurat', 'Informasi layanan untuk kebutuhan darurat.'],
                ] as $layanan)
                    <article class="rounded-xl border border-slate-200 bg-white p-6 transition hover:-translate-y-1 hover:shadow-md">
                        <div class="mb-4 h-1.5 w-12 rounded-full bg-oasis-teal"></div>
                        <h3 class="font-semibold text-oasis-navy">
                            {{ $layanan[0] }}
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">
                            {{ $layanan[1] }}
                        </p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Tentang Kami --}}
    <section id="tentang" class="mx-auto max-w-7xl px-6 py-16 md:py-20">
        <div class="grid items-center gap-8 md:grid-cols-2">
            <div>
                <p class="font-semibold text-oasis-teal">Tentang Kami</p>
                <h2 class="mt-2 text-3xl font-bold text-oasis-navy">
                    Mendukung pelayanan kesehatan yang lebih mudah diakses
                </h2>
            </div>

            <div>
                <p class="leading-7 text-slate-600">
                    PT Oasis Husada menjadi identitas rumah sakit dalam proyek
                    pengembangan sistem informasi ini. Sistem dirancang untuk
                    membantu proses pendaftaran pasien, pengelolaan kunjungan,
                    pencatatan pemeriksaan, pengelolaan resep, dan administrasi
                    pembayaran dalam satu platform.
                </p>
            </div>
        </div>
    </section>

    {{-- Ajakan --}}
    <section class="px-6 pb-16">
        <div class="mx-auto max-w-7xl rounded-2xl bg-oasis-navy px-6 py-10 text-center md:px-12 md:py-14">
            <h2 class="text-2xl font-bold text-white md:text-3xl">
                Mulai pendaftaran kunjungan Anda
            </h2>
            <p class="mx-auto mt-3 max-w-xl leading-7 text-slate-200">
                Buat akun pasien untuk melanjutkan proses pendaftaran melalui sistem.
            </p>
            <a href="{{ route('register') }}"
               class="mt-6 inline-flex rounded-lg bg-white px-6 py-3 font-semibold text-oasis-navy transition hover:bg-slate-100">
                Daftar sebagai Pasien
            </a>
        </div>
    </section>
@endsection