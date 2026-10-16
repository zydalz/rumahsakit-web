<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'PT Oasis Husada')</title>

    <meta
        name="description"
        content="Sistem informasi dan layanan kesehatan PT Oasis Husada."
    >

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-white font-sans text-slate-700 antialiased">

    {{-- Navbar --}}
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}"
               class="flex items-center gap-3"
               aria-label="PT Oasis Husada, beranda">

                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-oasis-blue text-sm font-bold text-white">
                    OH
                </span>

                <span>
                    <span class="block text-base font-bold text-oasis-navy">
                        Oasis Husada
                    </span>
                    <span class="block text-xs text-slate-500">
                        Sistem Informasi Rumah Sakit
                    </span>
                </span>
            </a>

            
{{-- Navigasi --}}
<nav aria-label="Navigasi utama"
     class="order-3 flex w-full flex-wrap items-center gap-x-6 gap-y-3 text-sm font-medium md:order-none md:w-auto">

    <a href="{{ route('home') }}"
       class="text-oasis-blue hover:text-oasis-teal">
        Beranda
    </a>

    <a href="{{ route('home') }}#tentang"
       class="text-slate-600 transition hover:text-oasis-blue">
        Tentang Kami
    </a>

    <a href="{{ route('home') }}#layanan"
       class="text-slate-600 transition hover:text-oasis-blue">
        Layanan
    </a>

    @auth
        @if (auth()->user()->role === 'pasien')
            <a href="{{ route('pasien.pendaftaran.index') }}"
               class="text-slate-600 hover:text-oasis-blue">
                Pendaftaran
            </a>
        @elseif (auth()->user()->role === 'dokter')
            <a href="{{ route('dokter.pemeriksaan.index') }}"
               class="text-slate-600 hover:text-oasis-blue">
                Pemeriksaan
            </a>
        @elseif (auth()->user()->role === 'farmasi')
            <a href="{{ route('farmasi.obat.index') }}"
               class="text-slate-600 hover:text-oasis-blue">
                Data Obat
            </a>
        @elseif (auth()->user()->role === 'admin')
            <a href="{{ route('admin.pasien.index') }}"
               class="text-slate-600 hover:text-oasis-blue">
                Data Pasien
            </a>
        @endif
    @endauth
</nav>


            
{{-- Aksi pengguna --}}
<div class="flex flex-wrap items-center gap-3">
    @auth
        <span class="hidden text-sm text-slate-600 sm:inline">
            {{ auth()->user()->name }}
        </span>

        <a href="{{ route('dashboard') }}"
           class="rounded-lg bg-oasis-blue px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-oasis-navy">
            Dashboard
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="rounded-lg border border-slate-200 px-3 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                Logout
            </button>
        </form>
    @else
        <a href="{{ route('login') }}"
           class="hidden rounded-lg px-3 py-2.5 text-sm font-semibold text-oasis-blue hover:bg-blue-50 sm:inline-flex">
            Login
        </a>

        <a href="{{ route('register') }}"
           class="rounded-lg bg-oasis-blue px-4 py-2.5 text-sm font-semibold text-white hover:bg-oasis-navy">
            Daftar
        </a>
    @endauth
</div>

        </div>
    </header>

    {{-- Konten halaman --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-6 py-8 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <p class="font-bold text-oasis-navy">Oasis Husada</p>
                <p class="mt-1 text-sm text-slate-500">
                    Sistem Informasi Rumah Sakit
                </p>
            </div>

            <p class="text-sm text-slate-500">
                &copy; {{ date('Y') }} PT Oasis Husada.
                Seluruh hak cipta dilindungi.
            </p>
        </div>
    </footer>

</body>
</html>