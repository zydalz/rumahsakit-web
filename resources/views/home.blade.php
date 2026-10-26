@extends('layouts.public')

@section('title', 'Beranda - Rumah Sakit Oasis Husada')

@section('content')
    {{-- Hero Banner Dinamis --}}
    <section class="bg-oasis-bg border-b border-slate-200">
        <div class="mx-auto grid max-w-7xl items-center gap-10 px-6 py-16 md:grid-cols-2 md:py-20">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-white px-4 py-1.5 text-xs font-semibold text-oasis-teal shadow-sm border border-slate-200">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pelayanan Kesehatan Terpadu & Terpercaya
                </span>

                <h1 class="mt-6 text-4xl font-extrabold leading-tight text-oasis-navy md:text-5xl">
                    Sehat Bersama
                    <span class="text-oasis-blue">RS Oasis Husada</span>
                </h1>

                <p class="mt-5 max-w-xl text-base leading-7 text-slate-600">
                    Melayani sepenuh hati dengan dokter spesialis berpengalaman, fasilitas medis mutakhir, dan pendaftaran rawat jalan online tanpa perlu antre lama.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('register') }}"
                       class="rounded-xl bg-oasis-blue px-6 py-3 font-semibold text-white shadow-md transition hover:bg-oasis-navy hover:shadow-lg">
                        Daftar Berobat Online
                    </a>

                    <a href="#poliklinik"
                       class="rounded-xl border border-slate-300 bg-white px-6 py-3 font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Jadwal & Poliklinik
                    </a>
                </div>
            </div>

            <div class="relative">
                <img
                    src="{{ $bannerUtama?->gambar_url ?? 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=1200&q=85' }}"
                    alt="{{ $bannerUtama?->judul ?? 'Pelayanan Dokter Oasis Husada' }}"
                    class="h-72 w-full rounded-2xl object-cover shadow-xl md:h-[420px] border border-slate-200"
                >
                <div class="absolute -bottom-5 left-5 rounded-xl bg-white/95 p-4 shadow-lg backdrop-blur-sm md:left-8 border border-slate-100 max-w-sm">
                    <p class="font-bold text-oasis-navy text-sm">{{ $bannerUtama?->judul ?? 'Pelayanan Berorientasi Pasien' }}</p>
                    <p class="mt-1 text-xs text-slate-500">{{ $bannerUtama?->deskripsi ?? 'Mengutamakan kenyamanan, ketepatan diagnosa, dan pemulihan Anda.' }}</p>
                </div>
            </div>
        </div>
    </section>

    {{-- UGD 24 Jam & Layanan Gawat Darurat (Sub-Task 4.4) --}}
    <section class="bg-gradient-to-r from-rose-700 via-rose-600 to-red-700 text-white shadow-md">
        <div class="mx-auto max-w-7xl px-6 py-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-4 text-center md:text-left">
                <div class="hidden sm:flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-white/20 backdrop-blur-sm">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center justify-center md:justify-start gap-2">
                        <span class="inline-block h-2.5 w-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <h3 class="text-base font-bold uppercase tracking-wider">Instalasi Gawat Darurat (UGD 24 Jam Siaga)</h3>
                    </div>
                    <p class="text-xs text-rose-100 mt-0.5">Penanganan medis gawat darurat, resusitasi trauma, dan armada ambulans siaga 24 jam setiap hari.</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-3">
                <a href="tel:02188997722" class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-rose-700 shadow transition hover:bg-rose-50">
                    <svg class="h-4 w-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 4V3z"></path></svg>
                    UGD Hotline: (021) 8899-7722
                </a>
            </div>
        </div>
    </section>

    {{-- Direktori Poliklinik Aktif --}}
    <section id="poliklinik" class="mx-auto max-w-7xl px-6 py-16">
        <div class="text-center max-w-2xl mx-auto">
            <p class="font-semibold text-oasis-teal text-sm uppercase tracking-wider">Poliklinik Spesialis & Umum</p>
            <h2 class="mt-2 text-3xl font-extrabold text-oasis-navy">
                Layanan Rawat Jalan Terlengkap
            </h2>
            <p class="mt-3 text-sm leading-6 text-slate-600">
                Konsultasikan keluhan kesehatan Anda ke poliklinik yang tepat dengan dukungan dokter spesialis bersertifikasi.
            </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($polis as $poli)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700 font-bold text-sm">
                                {{ substr($poli->nama_poli, 0, 2) }}
                            </span>
                            <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                {{ $poli->dokter->count() }} Dokter
                            </span>
                        </div>
                        <h3 class="text-base font-bold text-slate-800">{{ $poli->nama_poli }}</h3>
                        <p class="mt-2 text-xs text-slate-500 leading-relaxed">{{ $poli->deskripsi ?? 'Pelayanan rawat jalan terpadu.' }}</p>
                    </div>

                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-oasis-blue hover:text-oasis-navy inline-flex items-center gap-1">
                            Daftar Kunjungan &rarr;
                        </a>
                    </div>
                </div>
            @empty
                <p class="text-center col-span-4 text-slate-400 py-8">Belum ada poliklinik terdaftar.</p>
            @endforelse
        </div>
    </section>

    {{-- Fasilitas Medis Rumah Sakit (Dinamis dari GaleriAset) --}}
    @if(isset($fasilitas) && $fasilitas->isNotEmpty())
        <section class="bg-slate-50 py-16 border-t border-slate-200">
            <div class="mx-auto max-w-7xl px-6">
                <div class="text-center max-w-2xl mx-auto">
                    <p class="font-semibold text-oasis-teal text-sm uppercase tracking-wider">Fasilitas Modern</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-oasis-navy">
                        Kenyamanan & Teknologi Medis
                    </h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Fasilitas penunjang medis berstandar tinggi untuk memastikan pelayanan kesehatan yang optimal.
                    </p>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($fasilitas as $item)
                        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                            <div class="h-44 w-full overflow-hidden bg-slate-100">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->judul }}" class="h-full w-full object-cover transition duration-300 hover:scale-105">
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-slate-800 text-sm">{{ $item->judul }}</h3>
                                <p class="mt-1.5 text-xs text-slate-500 leading-relaxed">{{ $item->deskripsi }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Galeri Kegiatan & Edukasi (Dinamis dari GaleriAset) --}}
    @if(isset($galeriKegiatan) && $galeriKegiatan->isNotEmpty())
        <section class="mx-auto max-w-7xl px-6 py-16">
            <div class="text-center max-w-2xl mx-auto">
                <p class="font-semibold text-oasis-teal text-sm uppercase tracking-wider">Dokumentasi & Kegiatan</p>
                <h2 class="mt-2 text-3xl font-extrabold text-oasis-navy">
                    Aktivitas & Edukasi Kesehatan
                </h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Dedikasi kami bersama masyarakat untuk meningkatkan derajat kesehatan yang berkualitas.
                </p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-2">
                @foreach($galeriKegiatan as $kegiatan)
                    <div class="flex flex-col sm:flex-row overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="h-48 sm:h-auto sm:w-1/2 overflow-hidden bg-slate-100 shrink-0">
                            <img src="{{ $kegiatan->gambar_url }}" alt="{{ $kegiatan->judul }}" class="h-full w-full object-cover">
                        </div>
                        <div class="p-6 flex flex-col justify-between">
                            <div>
                                <span class="rounded-full bg-sky-50 px-2.5 py-0.5 text-xs font-semibold text-sky-700">Kegiatan Medis</span>
                                <h3 class="mt-2 font-bold text-slate-800 text-base">{{ $kegiatan->judul }}</h3>
                                <p class="mt-2 text-xs text-slate-500 leading-relaxed">{{ $kegiatan->deskripsi }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Ajakan Pendaftaran --}}
    <section class="px-6 pb-16">
        <div class="mx-auto max-w-7xl rounded-3xl bg-gradient-to-r from-oasis-navy to-sky-900 px-6 py-12 text-center shadow-xl md:px-12 md:py-16">
            <h2 class="text-2xl font-extrabold text-white md:text-3xl">
                Daftar Kunjungan Dokter Lebih Mudah & Nyaman
            </h2>
            <p class="mx-auto mt-3 max-w-xl text-sm leading-relaxed text-slate-200">
                Pilih poliklinik, tentukan jadwal praktik dokter spesialis pilihan Anda, dan pantau estimasi antrean langsung melalui ponsel Anda.
            </p>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('register') }}"
                   class="rounded-xl bg-white px-6 py-3 text-sm font-bold text-oasis-navy shadow-md transition hover:bg-slate-100">
                    Daftar sebagai Pasien
                </a>
                <a href="{{ route('login') }}"
                   class="rounded-xl border border-white/30 bg-white/10 px-6 py-3 text-sm font-bold text-white backdrop-blur-sm transition hover:bg-white/20">
                    Masuk ke Akun
                </a>
            </div>
        </div>
    </section>
@endsection