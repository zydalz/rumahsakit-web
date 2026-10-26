
@extends('layouts.public')

@section('title', 'Registrasi Pasien - PT Oasis Husada')

@section('content')
<section class="flex items-center justify-center bg-oasis-bg px-6 py-12">
    <div class="w-full max-w-lg">

        {{-- Judul --}}
        <div class="mb-8 text-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo PT Oasis Husada" class="mx-auto h-16 w-16 rounded-2xl object-contain bg-white p-1 border border-slate-200 shadow-sm">

            <h1 class="mt-5 text-2xl font-bold text-oasis-navy">
                Registrasi Pasien
            </h1>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                Buat akun untuk mengakses layanan PT Oasis Husada.
            </p>
        </div>

        {{-- Form registrasi --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">

            @if (session('success'))
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            @endif

            @if (session('error'))
                <x-alert type="error">
                    {{ session('error') }}
                </x-alert>
            @endif

            @if ($errors->any())
                <x-alert type="error">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form method="POST" action="{{ route('register.store') }}">
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                        Nama lengkap
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        maxlength="100"
                        required
                        autocomplete="name"
                        autofocus
                        placeholder="Masukkan nama lengkap"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
                    >
                </div>

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
                        placeholder="nama@email.com"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
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
                        autocomplete="new-password"
                        placeholder="Buat password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
                    >

                    <p class="mt-2 text-xs text-slate-500">
                        Gunakan password yang kuat dan mudah Anda kelola dengan aman.
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-slate-700">
                        Konfirmasi password
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Masukkan ulang password"
                        class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition placeholder:text-slate-400 focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
                    >
                </div>

                <x-button type="submit" class="mt-5 w-full">
                    Buat Akun Pasien
                </x-button>
            </form>

            <div class="my-6 border-t border-slate-200"></div>

            <p class="text-center text-sm text-slate-600">
                Sudah memiliki akun?
                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-oasis-blue hover:text-oasis-teal"
                >
                    Login
                </a>
            </p>
        </div>

        <p class="mt-6 text-center text-xs leading-5 text-slate-500">
            Pendaftaran akun ini ditujukan untuk pasien.
        </p>
    </div>
</section>
@endsection