<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - PT Oasis Husada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-700 antialiased">

@php
    $user = auth()->user();
    $role = $user?->role;

    $menus = match ($role) {
        'dokter' => [
            ['label' => 'Dashboard', 'route' => 'dokter.dashboard'],
            ['label' => 'Pemeriksaan Pasien', 'route' => 'dokter.pemeriksaan.index'],
            ['label' => 'Display Antrean', 'route' => 'antrean.display'],
        ],
        'farmasi' => [
            ['label' => 'Dashboard', 'route' => 'farmasi.index'],
            ['label' => 'Data Obat', 'route' => 'farmasi.obat.index'],
        ],
        'kasir' => [
            ['label' => 'Dashboard', 'route' => 'dashboard'],
            ['label' => 'Daftar Tagihan', 'route' => 'tagihan.index'],
        ],
        'admin' => [
            ['label' => 'Dashboard', 'route' => 'dashboard'],
            ['label' => 'Verifikasi Pasien', 'route' => 'admin.pendaftaran.index'],
            ['label' => 'Data Pasien', 'route' => 'admin.pasien.index'],
            ['label' => 'Resep & Farmasi', 'route' => 'farmasi.index'],
            ['label' => 'Data Obat', 'route' => 'farmasi.obat.index'],
            ['label' => 'Display Antrean', 'route' => 'antrean.display'],
        ],
        default => [],
    };
@endphp

<div class="min-h-screen md:flex">

    {{-- Sidebar --}}
    <aside class="w-full shrink-0 border-b border-slate-200 bg-white md:min-h-screen md:w-64 md:border-b-0 md:border-r">
        <div class="flex items-center gap-3 border-b border-slate-100 px-6 py-5">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Oasis Husada" class="h-11 w-11 rounded-xl object-contain bg-white p-0.5 border border-slate-200">

            <div>
                <p class="font-bold text-slate-800">Oasis Husada</p>
                <p class="text-xs text-slate-500">Sistem Informasi Rumah Sakit</p>
            </div>
        </div>

        <div class="px-5 py-5">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">
                Menu Utama
            </p>

            <nav class="mt-3 flex flex-wrap gap-2 md:flex-col">
                @foreach ($menus as $menu)
                    @if (\Illuminate\Support\Facades\Route::has($menu['route']))
                        <a href="{{ route($menu['route']) }}"
                           class="rounded-lg px-4 py-3 text-sm font-medium transition
                           {{ request()->routeIs($menu['route'])
                               ? 'bg-sky-50 text-sky-800'
                               : 'text-slate-600 hover:bg-slate-50 hover:text-sky-800' }}">
                            {{ $menu['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>

        <div class="border-t border-slate-100 px-5 py-5 md:mt-4">
            <p class="text-sm font-semibold text-slate-800">
                {{ $user?->name ?? 'Pengguna' }}
            </p>
            <p class="mt-1 text-xs text-slate-500">
                {{ ucfirst($role ?? 'pengguna') }}
            </p>

            <a href="{{ route('home') }}"
               class="mt-4 block rounded-lg px-4 py-2 text-sm text-slate-600 hover:bg-slate-50">
                Beranda Publik
            </a>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit"
                        class="w-full rounded-lg px-4 py-2 text-left text-sm font-medium text-red-600 hover:bg-red-50">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Konten --}}
    <div class="min-w-0 flex-1">
        <header class="border-b border-slate-200 bg-white px-6 py-4 sm:px-8">
            <p class="text-sm text-slate-500">
                PT Oasis Husada
            </p>
            <h1 class="mt-1 text-lg font-semibold text-slate-800">
                @yield('header', 'Dashboard')
            </h1>
        </header>

        <main class="p-5 sm:p-8">
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

            @yield('content')
        </main>
    </div>

</div>
</body>
</html>
