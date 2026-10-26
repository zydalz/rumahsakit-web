
@extends('layouts.public')

@section('title', 'Login - PT Oasis Husada')

@section('content')
<section class="flex min-h-[calc(100vh-160px)] items-center justify-center bg-oasis-bg px-6 py-12">
    <div class="w-full max-w-md">

        {{-- Judul --}}
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo PT Oasis Husada" class="mx-auto h-16 w-16 rounded-2xl object-contain bg-white p-1 border border-slate-200 shadow-sm">

            <h1 class="mt-5 text-2xl font-bold text-oasis-navy">
                Selamat Datang Kembali
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                Masuk ke akun Anda untuk melanjutkan layanan kesehatan.
            </p>
        </div>

        {{-- Form login --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">  
            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            {{-- Notifikasi error --}}
            @if (session('error'))
                <x-alert type="error">
                    {{ session('error') }}
                </x-alert>
            @endif

            {{-- Validasi form --}}
            @if ($errors->any())
                <x-alert type="error">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf
                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        maxlength="150"
                        required
                        autocomplete="email"
                        autofocus
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
                        placeholder="nama@email.com"
                    >
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
                        placeholder="Masukkan password"
                    >
                </div>

                <x-button type="submit" class="mt-5 w-full">
                    Login ke Akun
                </x-button>
            </form>

            <div class="my-6 border-t border-slate-200"></div>

            <p class="text-center text-sm text-slate-600">
                Belum memiliki akun?
                <a
                    href="{{ route('register') }}"
                    class="font-semibold text-oasis-blue hover:text-oasis-teal"
                >
                    Daftar sebagai pasien
                </a>
            </p>
        </div>

        <p class="mt-6 text-center text-xs leading-5 text-slate-500">
            Gunakan akun Anda untuk mengakses layanan sesuai hak akses yang diberikan.
        </p>
    </div>
</section>
@endsection
