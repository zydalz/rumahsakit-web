@extends('layouts.app')

@section('title', 'Dashboard Eksekutif - Kepala Rumah Sakit')
@section('header', 'Dashboard Eksekutif & Pengawasan')

@section('content')
<div class="space-y-6">

    {{-- Salam Pimpinan & Aksi Cepat --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Ringkasan Eksekutif Rumah Sakit
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Pemantauan pendapatan kasir, trafik kunjungan poliklinik, dan performa pelayanan secara menyeluruh.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('kepala-rs.laporan') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Laporan Kinerja & Pelayanan
            </a>

            <a href="{{ route('antrean.display') }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Layar Display Antrean
            </a>
        </div>
    </div>

    {{-- Grid Kartu Ringkasan Finansial & Operasional --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {{-- Pendapatan Hari Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Penerimaan Kasir Hari Ini</span>
                <span class="rounded-lg bg-emerald-50 p-2 text-emerald-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-extrabold text-slate-800">
                Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-400">Total penerimaan kasir hari ini</p>
        </div>

        {{-- Pendapatan Bulan Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Penerimaan Bulan Ini</span>
                <span class="rounded-lg bg-sky-50 p-2 text-sky-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-extrabold text-slate-800">
                Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-400">Akumulasi {{ now()->translatedFormat('F Y') }}</p>
        </div>

        {{-- Kunjungan Pasien Bulan Ini --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Kunjungan Pasien Poli</span>
                <span class="rounded-lg bg-indigo-50 p-2 text-indigo-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-extrabold text-slate-800">
                {{ number_format($kunjunganBulanIni, 0, ',', '.') }} <span class="text-sm font-normal text-slate-500">Pasien</span>
            </p>
            <p class="mt-1 text-xs text-slate-400">Hari ini: {{ $kunjunganHariIni }} kunjungan</p>
        </div>

        {{-- Kesiapan Fasilitas & Obat --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500">Stok Obat Farmasi</span>
                <span class="rounded-lg bg-rose-50 p-2 text-rose-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-extrabold text-slate-800">
                {{ $obatKritisCount }} <span class="text-sm font-normal text-rose-600 font-semibold">Kritis</span>
            </p>
            <p class="mt-1 text-xs text-slate-400">Dari {{ $totalItemObat }} total jenis obat aktif</p>
        </div>
    </div>

    {{-- Bagian Tengah: Sebaran Poli & Tren Morbiditas Penyakit --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Sebaran Kunjungan per Poli --}}
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-slate-800">Sebaran Pasien per Poliklinik</h3>
                    <p class="text-xs text-slate-500">Aktivitas kunjungan rawat jalan selama {{ now()->translatedFormat('F Y') }}</p>
                </div>
                <span class="text-xs font-semibold text-sky-700 bg-sky-50 px-2.5 py-1 rounded-md border border-sky-200">
                    {{ $poliAktif }} Poli Aktif
                </span>
            </div>

            <div class="space-y-4">
                @forelse($sebaranPoli as $poli)
                    @php
                        $persen = $kunjunganBulanIni > 0 ? round(($poli->pendaftarans_count / $kunjunganBulanIni) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span class="font-semibold text-slate-700">{{ $poli->nama_poli }}</span>
                            <span class="text-slate-500 font-medium">{{ $poli->pendaftarans_count }} Pasien ({{ $persen }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="bg-sky-600 h-2.5 rounded-full transition-all duration-500" style="width: {{ max(4, $persen) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400 text-center py-6">Belum ada data poliklinik terdaftar.</p>
                @endforelse
            </div>
        </div>

        {{-- Tren Kasus Morbiditas (5 Penyakit Terbanyak) --}}
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-800">Tren Kasus Penyakit Terbanyak</h3>
                <p class="text-xs text-slate-500 mb-4">Diagnosa klinis terbanyak yang ditangani dokter bulan ini</p>

                <div class="space-y-3">
                    @forelse($topPenyakit as $index => $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-sky-100 text-sky-800 font-bold text-xs">
                                    {{ $index + 1 }}
                                </span>
                                <span class="font-semibold text-slate-700">{{ $item->diagnosis }}</span>
                            </div>
                            <span class="font-mono font-bold text-slate-900 bg-white px-2 py-1 rounded border border-slate-200">
                                {{ $item->total }} kasus
                            </span>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            Belum ada diagnosa tercatat untuk periode ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="mt-4 pt-4 border-t border-slate-100 text-right">
                <a href="{{ route('kepala-rs.laporan') }}" class="text-xs text-sky-700 hover:text-sky-800 font-semibold inline-flex items-center gap-1">
                    Lihat 10 Besar Morbiditas Penyakit Lengkap &rarr;
                </a>
            </div>
        </div>

    </div>

    {{-- Transaksi Pembayaran Kasir Terkini --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <div>
                <h3 class="font-bold text-slate-800">Transaksi Penerimaan Kasir Terkini</h3>
                <p class="text-xs text-slate-500">Pantauan pembayaran pasien yang masuk ke kasir secara langsung</p>
            </div>
            <span class="text-xs text-slate-400">Menampilkan 6 transaksi terakhir</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-6 py-3.5">Waktu Transaksi</th>
                        <th class="px-6 py-3.5">Nomor Tagihan</th>
                        <th class="px-6 py-3.5">Nama Pasien</th>
                        <th class="px-6 py-3.5">Metode Bayar</th>
                        <th class="px-6 py-3.5 text-right">Jumlah Dibayar</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($transaksiTerbaru as $trx)
                        <tr class="transition hover:bg-slate-50/80">
                            <td class="px-6 py-3.5 text-slate-500 whitespace-nowrap">
                                {{ $trx->tanggal_bayar ? \Carbon\Carbon::parse($trx->tanggal_bayar)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-6 py-3.5 font-mono font-semibold text-slate-700 whitespace-nowrap">
                                {{ $trx->tagihan->nomor_tagihan ?? '-' }}
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-slate-800">
                                {{ $trx->tagihan->pendaftaran->pasien->nama ?? 'Pasien' }}
                            </td>
                            <td class="px-6 py-3.5 whitespace-nowrap">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-semibold uppercase
                                    {{ $trx->metode === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($trx->metode === 'qris' ? 'bg-sky-100 text-sky-800' : 'bg-purple-100 text-purple-800') }}">
                                    {{ $trx->metode }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 font-mono font-bold text-slate-900 text-right whitespace-nowrap">
                                Rp {{ number_format($trx->jumlah_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-slate-400 text-xs">
                                Belum ada transaksi kasir yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
