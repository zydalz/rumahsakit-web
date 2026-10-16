@extends('layouts.app')

@section('title', $title)
@section('header', $title)

@section('content')
<div class="mx-auto max-w-7xl px-6 py-10">
    <div class="mb-8">
        <p class="text-sm font-semibold text-sky-700">
            PT Oasis Husada
        </p>

        <h1 class="mt-2 text-3xl font-bold text-slate-800">
            {{ $title }}
        </h1>

        <p class="mt-2 text-slate-500">
            Selamat datang, {{ auth()->user()->name }}.
            {{ $description }}
        </p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-5 sm:grid-cols-2">
        @foreach ($menus as $menu)
            <a href="{{ route($menu['route']) }}"
               class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:border-sky-300 hover:shadow-md">
                <h2 class="font-semibold text-slate-800">
                    {{ $menu['label'] }}
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-500">
                    {{ $menu['description'] }}
                </p>

                <span class="mt-5 inline-block text-sm font-semibold text-sky-700">
                    Buka halaman &rarr;
                </span>
            </a>
        @endforeach
    </div>
</div>
@endsection