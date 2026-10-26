@extends('layouts.app')

@section('title', 'Dashboard Kasir - PT Oasis Husada')
@section('header', 'Dashboard Kasir & Tagihan')

@section('content')
<div class="space-y-8 p-6 lg:p-8">

    {{-- Salam & Pengantar --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Laporan & Transaksi Kasir
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola pembayaran tagihan, pantau piutang pasien, dan cetak bukti transaksi.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('tagihan.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-[#173D59] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#245779]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Semua Tagihan Pasien
            </a>

            @if(\Illuminate\Support\Facades\Route::has('tagihan.create'))
                <a href="{{ route('tagihan.create') }}"
                   class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    + Buat Tagihan Baru
                </a>
            @endif
        </div>
    </div>

    {{-- Kartu Metrik Keuangan Kasir --}}
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

        {{-- Penerimaan Hari Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Penerimaan Hari Ini</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-emerald-700">Rp{{ number_format($stats['penerimaan_hari_ini'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">Pembayaran masuk per {{ date('d M Y') }}</p>
            </div>
        </div>

        {{-- Jumlah Transaksi Hari Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Transaksi Sukses</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-50 text-sky-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-slate-800">{{ number_format($stats['transaksi_hari_ini']) }}</p>
                <p class="mt-1 text-xs text-slate-400">Transaksi kasir berhasil hari ini</p>
            </div>
        </div>

        {{-- Tagihan Belum Lunas --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Tagihan Menunggu</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-50 text-amber-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-3xl font-bold text-amber-600">{{ number_format($stats['tagihan_belum_lunas']) }}</p>
                <p class="mt-1 text-xs text-slate-400">Tagihan berstatus belum lunas</p>
            </div>
        </div>

        {{-- Total Piutang Pasien --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-slate-500">Sisa Piutang</span>
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-rose-50 text-rose-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                    </svg>
                </span>
            </div>
            <div class="mt-4">
                <p class="text-2xl font-bold text-rose-600">Rp{{ number_format($stats['total_piutang'], 0, ',', '.') }}</p>
                <p class="mt-1 text-xs text-slate-400">Total nominal belum terbayar</p>
            </div>
        </div>

    </div>

    {{-- Konten Grid: Tagihan Menunggu Pembayaran & Transaksi Terbaru --}}
    <div class="grid gap-8 lg:grid-cols-2">

        {{-- Kolom Kiri: Tagihan Menunggu Pembayaran --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Tagihan Perlu Diproses
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Daftar tagihan yang belum lunas atau baru dicicil sebagian
                    </p>
                </div>

                <a href="{{ route('tagihan.index') }}" class="text-xs font-semibold text-[#173D59] hover:text-[#245779]">
                    Lihat Semua &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3">No. Tagihan / Pasien</th>
                            <th class="px-6 py-3 text-right">Sisa Tagihan</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($tagihanPerluBayar as $tagihan)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">{{ $tagihan->nomor_tagihan }}</p>
                                    <p class="text-xs text-slate-500">
                                        {{ $tagihan->pendaftaran?->pasien?->nama ?? 'Pasien tidak ditemukan' }}
                                        (RM: {{ $tagihan->pendaftaran?->pasien?->nomor_rm ?? '-' }})
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <p class="font-bold text-rose-600">
                                        Rp{{ number_format($tagihan->sisa_tagihan, 0, ',', '.') }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        Total: Rp{{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('tagihan.show', $tagihan->id) }}"
                                       class="inline-block rounded-lg bg-[#173D59] px-3 py-1.5 text-xs font-semibold text-white hover:bg-[#245779]">
                                        Bayar Kasir
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center text-sm text-slate-400">
                                    Tidak ada tagihan yang menunggu pembayaran saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Kolom Kanan: Transaksi Kasir Terbaru --}}
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 p-6">
                <div>
                    <h2 class="text-base font-bold text-slate-800">
                        Transaksi Pembayaran Terkini
                    </h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        Bukti pembayaran terakhir yang diterima kasir
                    </p>
                </div>

                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                    Status Berhasil
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-6 py-3">Waktu / Pasien</th>
                            <th class="px-6 py-3">Metode</th>
                            <th class="px-6 py-3 text-right">Nominal</th>
                            <th class="px-6 py-3 text-center">Nota</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($transaksiTerbaru as $bayar)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <p class="font-semibold text-slate-800">
                                        {{ $bayar->tagihan?->pendaftaran?->pasien?->nama ?? 'Pasien' }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ \Illuminate\Support\Carbon::parse($bayar->tanggal_bayar)->format('d/m/Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 uppercase text-xs font-semibold text-slate-600">
                                    {{ $bayar->metode }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-700">
                                    Rp{{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('tagihan.pembayaran.nota', ['tagihan' => $bayar->tagihan_id, 'pembayaran' => $bayar->id]) }}"
                                       target="_blank"
                                       class="inline-block rounded border border-slate-200 px-2.5 py-1 text-xs font-medium text-[#173D59] hover:bg-slate-100">
                                        Nota
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-sm text-slate-400">
                                    Belum ada transaksi pembayaran tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
@endsection
