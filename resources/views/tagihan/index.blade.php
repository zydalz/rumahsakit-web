@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#173D59]">
                Daftar Tagihan
            </h1>
            <p class="mt-1 text-sm text-gray-600">
                Informasi tagihan, pembayaran, dan sisa yang harus dibayar pasien.
            </p>
        </div>

        <div class="flex flex-wrap gap-2">
            <button type="button" onclick="window.print()"
                class="rounded-lg border border-[#173D59] px-4 py-3 text-sm font-medium text-[#173D59] hover:bg-gray-100 print:hidden">
                Cetak Daftar
            </button>

            <a href="{{ route('tagihan.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-[#173D59] px-4 py-3 text-sm font-medium text-white hover:bg-[#245779] print:hidden">
                + Tambah Tagihan
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700 print:hidden">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[1050px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-4 py-4 font-semibold">No.</th>
                        <th class="px-4 py-4 font-semibold">Tagihan / Pasien</th>
                        <th class="px-4 py-4 font-semibold">Total Tagihan</th>
                        <th class="px-4 py-4 font-semibold">Sudah Dibayar</th>
                        <th class="px-4 py-4 font-semibold">Sisa Tagihan</th>
                        <th class="px-4 py-4 font-semibold">Status</th>
                        <th class="px-4 py-4 font-semibold print:hidden">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($tagihans as $tagihan)
                        @php
                            $totalTagihan = (float) $tagihan->total_tagihan;

                            $totalDibayar = (float) $tagihan->pembayaran
                                ->where('status', 'berhasil')
                                ->sum('jumlah_bayar');

                            $sisaTagihan = max(0, $totalTagihan - $totalDibayar);
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-4 py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-4">
                                <div class="font-semibold text-[#173D59]">
                                    {{ $tagihan->nomor_tagihan }}
                                </div>
                                <div class="mt-1 text-gray-800">
                                    {{ $tagihan->pendaftaran?->pasien?->nama ?? 'Pasien tidak ditemukan' }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    No. RM:
                                    {{ $tagihan->pendaftaran?->pasien?->nomor_rm ?? '-' }}
                                </div>
                                <div class="mt-1 text-xs text-gray-500">
                                    ID Pendaftaran: {{ $tagihan->pendaftaran_id }}
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 font-medium">
                                Rp{{ number_format($totalTagihan, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 text-green-700 font-medium">
                                Rp{{ number_format($totalDibayar, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 font-semibold {{ $sisaTagihan > 0 ? 'text-red-600' : 'text-green-700' }}">
                                Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-4">
                                @if ($tagihan->status === 'lunas')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Lunas
                                    </span>
                                @elseif ($tagihan->status === 'dibatalkan')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-4 py-4 print:hidden">
                                <a href="{{ route('tagihan.show', $tagihan->id) }}"
                                    class="inline-block rounded-lg bg-[#173D59] px-3 py-2 text-xs font-medium text-white hover:bg-[#245779]">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <p class="font-medium text-gray-700">
                                    Belum ada data tagihan
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tagihan akan muncul di sini setelah data tersedia.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <p class="mt-3 text-xs text-gray-500 print:hidden">
        Total dibayar hanya menghitung transaksi berstatus berhasil.
        Gunakan tombol Detail untuk melihat rincian dan riwayat pembayaran.
    </p>
</div>

<style>
    @media print {
        @page {
            size: landscape;
            margin: 12mm;
        }

        body {
            background: white !important;
        }

        body * {
            visibility: hidden;
        }

        main, main * {
            visibility: visible;
        }

        main {
            position: absolute;
            inset: 0;
            width: 100%;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .shadow-sm {
            box-shadow: none !important;
        }

        table {
            min-width: 0 !important;
            width: 100% !important;
        }

        tr {
            break-inside: avoid;
        }

        a {
            color: black !important;
            text-decoration: none !important;
        }
    }
</style>
@endsection