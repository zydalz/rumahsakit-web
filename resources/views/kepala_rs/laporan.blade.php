@extends('layouts.app')

@section('title', 'Laporan Kinerja & Pelayanan - Kepala Rumah Sakit')
@section('header', 'Laporan Kinerja & Pelayanan Eksekutif')

@section('content')
<div class="space-y-6">

    {{-- Filter Periode Laporan & Tombol Cetak --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form method="GET" action="{{ route('kepala-rs.laporan') }}" class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 flex-1 max-w-3xl">
                <div>
                    <label for="periode" class="block text-xs font-semibold text-slate-700 mb-1">Periode Laporan</label>
                    <select name="periode" id="periode" onchange="toggleCustomDates(this.value)"
                            class="w-full rounded-xl border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none focus:border-sky-500">
                        <option value="hari_ini" {{ $periode === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="bulan_ini" {{ $periode === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tahun_ini" {{ $periode === 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ $periode === 'custom' ? 'selected' : '' }}>Rentang Tanggal Khusus</option>
                    </select>
                </div>

                <div id="col-tanggal-mulai" class="{{ $periode === 'custom' ? '' : 'hidden' }}">
                    <label for="tanggal_mulai" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai"
                           value="{{ request('tanggal_mulai', $startDate->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 outline-none focus:border-sky-500">
                </div>

                <div id="col-tanggal-selesai" class="{{ $periode === 'custom' ? '' : 'hidden' }}">
                    <label for="tanggal_selesai" class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai"
                           value="{{ request('tanggal_selesai', $endDate->format('Y-m-d')) }}"
                           class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Terapkan Filter
                </button>

                <a href="{{ route('kepala-rs.laporan.cetak', request()->all()) }}" target="_blank"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    <svg class="h-4 w-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                    Cetak Laporan / PDF
                </a>
            </div>
        </form>

        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Periode Terpilih: <strong class="text-slate-800 font-semibold">{{ $labelPeriode }}</strong></span>
            <span>Rentang Tanggal: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</span>
        </div>
    </div>

    {{-- Ringkasan Indikator Kunci Periode Ini --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="text-xs font-semibold text-slate-500">Total Pendapatan Kasir</span>
            <p class="mt-2 text-2xl font-extrabold text-slate-800">
                Rp {{ number_format($totalPendapatanPeriode, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-400">{{ $totalTransaksiPeriode }} transaksi berhasil</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="text-xs font-semibold text-slate-500">Total Pasien Terdaftar</span>
            <p class="mt-2 text-2xl font-extrabold text-slate-800">
                {{ $totalKunjunganPeriode }} <span class="text-sm font-normal text-slate-500">Kunjungan</span>
            </p>
            <p class="mt-1 text-xs text-slate-400">{{ $kunjunganSelesai }} selesai diperiksa</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="text-xs font-semibold text-slate-500">Rata-rata Transaksi per Pasien</span>
            @php
                $rataRata = $totalTransaksiPeriode > 0 ? $totalPendapatanPeriode / $totalTransaksiPeriode : 0;
            @endphp
            <p class="mt-2 text-2xl font-extrabold text-slate-800">
                Rp {{ number_format($rataRata, 0, ',', '.') }}
            </p>
            <p class="mt-1 text-xs text-slate-400">Rata-rata nilai tagihan terbayar</p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="text-xs font-semibold text-slate-500">Kunjungan Batal</span>
            <p class="mt-2 text-2xl font-extrabold text-rose-600">
                {{ $kunjunganBatal }} <span class="text-sm font-normal text-slate-500">Pasien</span>
            </p>
            <p class="mt-1 text-xs text-slate-400">Dibatalkan oleh pasien/dokter</p>
        </div>
    </div>

    {{-- Grid 2 Kolom: Rekapitulasi Kasir & Kunjungan Poli --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 1. Rekapitulasi Metode Pembayaran Kasir --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-1">Penerimaan Kasir Berdasarkan Metode Bayar</h3>
            <p class="text-xs text-slate-500 mb-4">Rincian pendapatan kasir tunai vs nontunai (QRIS & Transfer)</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="py-2.5 px-3">Metode Bayar</th>
                            <th class="py-2.5 px-3 text-center">Jumlah Trx</th>
                            <th class="py-2.5 px-3 text-right">Total Penerimaan</th>
                            <th class="py-2.5 px-3 text-right">Porsi (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($rekapPembayaran as $bayar)
                            @php
                                $persenBayar = $totalPendapatanPeriode > 0 ? round(($bayar->total_nominal / $totalPendapatanPeriode) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-700 uppercase">{{ $bayar->metode }}</td>
                                <td class="py-2.5 px-3 text-center text-slate-600">{{ $bayar->jumlah_transaksi }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                    Rp {{ number_format($bayar->total_nominal, 0, ',', '.') }}
                                </td>
                                <td class="py-2.5 px-3 text-right text-slate-500 font-medium">{{ $persenBayar }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Belum ada transaksi pembayaran pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 2. Rekapitulasi Kunjungan per Poli --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-1">Beban Kunjungan per Poliklinik</h3>
            <p class="text-xs text-slate-500 mb-4">Sebaran pasien rawat jalan pada setiap unit poliklinik</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="py-2.5 px-3">Nama Poliklinik</th>
                            <th class="py-2.5 px-3 text-center">Total Kunjungan</th>
                            <th class="py-2.5 px-3 text-right">Porsi Kunjungan (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kunjunganPerPoli as $kPoli)
                            @php
                                $persenKunjungan = $totalKunjunganPeriode > 0 ? round(($kPoli->pendaftarans_count / $totalKunjunganPeriode) * 100, 1) : 0;
                            @endphp
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-700">{{ $kPoli->nama_poli }}</td>
                                <td class="py-2.5 px-3 text-center font-bold text-slate-800">{{ $kPoli->pendaftarans_count }}</td>
                                <td class="py-2.5 px-3 text-right text-slate-500 font-medium">{{ $persenKunjungan }}%</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-400">Belum ada poliklinik terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Grid 2 Kolom: Top Diagnosa Morbiditas & Kinerja Dokter --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- 3. Top 10 Diagnosa Penyakit Terbanyak --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-1">10 Besar Kasus Penyakit (Morbiditas)</h3>
            <p class="text-xs text-slate-500 mb-4">Penyakit yang paling sering ditangani oleh dokter rumah sakit</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="py-2.5 px-3">Peringkat</th>
                            <th class="py-2.5 px-3">Diagnosis Klinis</th>
                            <th class="py-2.5 px-3 text-right">Jumlah Kasus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($topDiagnosa as $index => $diag)
                            <tr>
                                <td class="py-2.5 px-3">
                                    <span class="inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-100 font-bold text-slate-700 text-[11px]">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="py-2.5 px-3 font-semibold text-slate-800">{{ $diag->diagnosis }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-sky-700">{{ $diag->total_kasus }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-400">Belum ada rekam medis pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Kinerja Dokter (Beban Pasien) --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-1">Kinerja & Beban Pelayanan Dokter</h3>
            <p class="text-xs text-slate-500 mb-4">Jumlah pasien yang ditangani oleh masing-masing dokter</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                        <tr>
                            <th class="py-2.5 px-3">Nama Dokter</th>
                            <th class="py-2.5 px-3">Poli</th>
                            <th class="py-2.5 px-3 text-right">Pasien Diperiksa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kinerjaDokter as $dok)
                            <tr>
                                <td class="py-2.5 px-3 font-semibold text-slate-800">{{ $dok->nama ?? $dok->user->name ?? 'Dokter' }}</td>
                                <td class="py-2.5 px-3 text-slate-500">{{ $dok->poli->nama_poli ?? '-' }}</td>
                                <td class="py-2.5 px-3 text-right font-mono font-bold text-emerald-700">
                                    {{ $dok->pemeriksaans_count }} Pasien
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-slate-400">Belum ada dokter terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- 5. Laporan Pemakaian Obat Terbanyak (Farmasi) --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <h3 class="font-bold text-slate-800 mb-1">10 Besar Obat Paling Banyak Diresepkan</h3>
        <p class="text-xs text-slate-500 mb-4">Persediaan obat yang paling cepat keluar dan sering dibutuhkan pasien</p>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[650px] text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 uppercase font-semibold">
                    <tr>
                        <th class="py-3 px-4">No.</th>
                        <th class="py-3 px-4">Nama Obat</th>
                        <th class="py-3 px-4">Satuan</th>
                        <th class="py-3 px-4 text-center">Frekuensi Resep</th>
                        <th class="py-3 px-4 text-right">Total Butir / Dikeluarkan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($topObat as $index => $obatItem)
                        <tr class="transition hover:bg-slate-50/70">
                            <td class="py-3 px-4 text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 font-semibold text-slate-800">{{ $obatItem->obat->nama_obat ?? 'Obat' }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $obatItem->obat->satuan ?? '-' }}</td>
                            <td class="py-3 px-4 text-center font-medium text-slate-600">{{ $obatItem->total_resep }} kali</td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-sky-700">
                                {{ number_format($obatItem->total_pengeluaran, 0, ',', '.') }} {{ $obatItem->obat->satuan ?? '' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Belum ada data resep obat pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    function toggleCustomDates(val) {
        const colMulai = document.getElementById('col-tanggal-mulai');
        const colSelesai = document.getElementById('col-tanggal-selesai');
        if (val === 'custom') {
            colMulai.classList.remove('hidden');
            colSelesai.classList.remove('hidden');
        } else {
            colMulai.classList.add('hidden');
            colSelesai.classList.add('hidden');
        }
    }
</script>
@endsection
