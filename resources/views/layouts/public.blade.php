
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Oasis Husada')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white font-sans text-oasis-navy antialiased">

    <header class="sticky top-0 z-50 border-b border-slate-100 bg-white/95 backdrop-blur">
        <nav class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-5 py-4 lg:px-8">
            <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-oasis-blue text-lg font-bold text-white">
                    OH
                </span>
                <span>
                    <span class="block text-base font-bold text-oasis-navy">Oasis Husada</span>
                    <span class="block text-xs text-slate-500">Pelayanan Kesehatan</span>
                </span>
            </a>

            <div class="hidden items-center gap-7 text-sm font-medium text-slate-600 md:flex">
                <a href="{{ url('/') }}" class="transition hover:text-oasis-blue">Beranda</a>
                <a href="{{ url('/#tentang') }}" class="transition hover:text-oasis-blue">Tentang Kami</a>
                <a href="{{ url('/#layanan') }}" class="transition hover:text-oasis-blue">Layanan</a>
                <a href="{{ url('/#dokter') }}" class="transition hover:text-oasis-blue">Dokter</a>
                <a href="{{ url('/#informasi') }}" class="transition hover:text-oasis-blue">Informasi</a>
            </div>

            <div class="flex shrink-0 items-center gap-2">
                @guest
                    <a href="{{ route('login') }}"
                       class="rounded-lg px-3 py-2 text-sm font-semibold text-oasis-blue hover:bg-oasis-bg">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="rounded-lg bg-oasis-blue px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-oasis-navy">
                        Daftar
                    </a>
                @else
                    <a href="{{ route('dashboard') }}"
                       class="rounded-lg bg-oasis-blue px-4 py-2.5 text-sm font-semibold text-white hover:bg-oasis-navy">
                        Dashboard
                    </a>
                @endguest
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer id="informasi" class="mt-16 bg-oasis-navy text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 py-10 sm:grid-cols-2 lg:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-lg font-bold">Oasis Husada</h2>
                <p class="mt-3 max-w-sm text-sm leading-6 text-slate-300">
                    Sistem informasi pelayanan kesehatan yang dirancang untuk
                    membantu pasien mengakses informasi dan layanan rumah sakit.
                </p>
            </div>

            <div>
                <h3 class="font-semibold">Navigasi</h3>
                <div class="mt-3 flex flex-col gap-2 text-sm text-slate-300">
                    <a href="{{ url('/') }}" class="hover:text-white">Beranda</a>
                    <a href="{{ url('/#tentang') }}" class="hover:text-white">Tentang Kami</a>
                    <a href="{{ url('/#layanan') }}" class="hover:text-white">Layanan</a>
                </div>
            </div>

            <div>
                <h3 class="font-semibold">Informasi</h3>
                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Informasi kontak dan alamat rumah sakit dapat ditambahkan
                    setelah data resminya tersedia.
                </p>
            </div>
        </div>

        <div class="border-t border-white/10 px-5 py-4 text-center text-xs text-slate-300">
            © {{ date('Y') }} PT Oasis Husada
        </div>
    </footer>

</body>
</html>