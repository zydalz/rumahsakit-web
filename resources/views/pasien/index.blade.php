@extends('layouts.public')

@section('title', 'Data Pasien - Oasis Husada')

@section('content')
<section class="min-h-screen bg-oasis-bg px-4 py-10 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">

        {{-- Header halaman --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="mb-2 text-sm font-medium text-oasis-teal">
                    Manajemen Rumah Sakit
                </p>
                <h1 class="text-2xl font-bold text-oasis-navy sm:text-3xl">
                    Data Pasien
                </h1>
                <p class="mt-2 text-sm text-slate-600">
                    Kelola informasi pasien PT Oasis Husada.
                </p>
            </div>

            <a href="{{ route('admin.pasien.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-oasis-blue px-5 py-3 text-sm font-semibold text-white transition hover:bg-oasis-navy">
                + Tambah Pasien
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="mb-5">
                <x-alert type="success">
                    {{ session('success') }}
                </x-alert>
            </div>
        @endif

        {{-- Pencarian --}}
        <div class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.pasien.index') }}"
                  class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, nomor rekam medis, atau NIK..."
                    class="w-full rounded-lg border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-oasis-blue focus:ring-2 focus:ring-oasis-blue/20"
                >

                <button type="submit"
                        class="rounded-lg border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Cari
                </button>

                @if (request('search'))
                    <a href="{{ route('admin.pasien.index') }}"
                       class="rounded-lg px-4 py-3 text-center text-sm font-medium text-slate-600 hover:bg-slate-50">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- Tabel pasien --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h2 class="font-semibold text-oasis-navy">
                    Daftar Pasien
                </h2>
                <span class="text-sm text-slate-500">
                    {{ $pasien->total() }} data
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr>
                            <th class="px-5 py-4 font-semibold">No.</th>
                            <th class="px-5 py-4 font-semibold">No. Rekam Medis</th>
                            <th class="px-5 py-4 font-semibold">Nama Pasien</th>
                            <th class="px-5 py-4 font-semibold">NIK</th>
                            <th class="px-5 py-4 font-semibold">No. Telepon</th>
                            <th class="px-5 py-4 text-right font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pasien as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-5 py-4 text-slate-500">
                                    {{ $pasien->firstItem() + $loop->index }}
                                </td>
                                <td class="px-5 py-4 font-medium text-oasis-blue">
                                    {{ $item->nomor_rm }}
                                </td>
                                <td class="px-5 py-4 font-medium text-slate-800">
                                    {{ $item->nama }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->nik ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-slate-600">
                                    {{ $item->no_telepon ?? '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.pasien.show', $item) }}"
                                           class="font-medium text-oasis-blue hover:underline">
                                            Detail
                                        </a>
                                        <a href="{{ route('admin.pasien.edit', $item) }}"
                                           class="font-medium text-oasis-teal hover:underline">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <p class="font-medium text-slate-700">
                                        Belum ada data pasien
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Tambahkan data pasien untuk mulai mengelola informasi pasien.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($pasien->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $pasien->withQueryString()->links() }}
                </div>
            @endif
        </div>

    </div>
</section>
@endsection
