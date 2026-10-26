@extends('layouts.app')

@section('title', 'Dashboard Farmasi')
@section('header', 'Dashboard Farmasi')

@section('content')
<div class="space-y-6">

    {{-- Header & Tindakan Cepat --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Layanan Farmasi & Pengeluaran Obat
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Verifikasi resep dokter, pantau status pembayaran kasir, dan serahkan obat ke pasien.
            </p>
        </div>

        <a href="{{ route('farmasi.obat.index') }}"
           class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Kelola Master Obat & Stok
        </a>
    </div>

    {{-- Kotak Panduan Alur Pelayanan --}}
    <div class="flex items-start gap-3 rounded-xl border border-sky-100 bg-sky-50/70 p-4 text-sm text-sky-800">
        <svg class="mt-0.5 h-5 w-5 shrink-0 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <span class="font-semibold">Alur Pelayanan Resep & Kasir:</span>
            Pasien harus menyelesaikan administrasi dan pembayaran di kasir terlebih dahulu. Tombol <span class="font-semibold text-emerald-700">"Serahkan Obat"</span> akan otomatis aktif setelah pembayaran lunas. Saat obat diserahkan, persediaan stok obat akan langsung terpotong secara otomatis dan tercatat dalam buku riwayat keluar-masuk obat.
        </div>
    </div>

    {{-- Kartu Ringkasan Data --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Total Seluruh Resep</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $stats['total_resep'] ?? $reseps->count() }}</p>
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-amber-700">Menunggu Pembayaran</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">{{ $stats['resep_menunggu'] ?? 0 }}</p>
        </div>

        <div class="rounded-xl border border-sky-200 bg-sky-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-sky-700">Siap Diserahkan (Lunas)</p>
            <p class="mt-2 text-2xl font-bold text-sky-700">{{ $stats['resep_siap'] ?? 0 }}</p>
        </div>

        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-emerald-700">Obat Telah Diserahkan</p>
            <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $stats['resep_selesai'] ?? 0 }}</p>
        </div>

        <div class="rounded-xl border border-rose-200 bg-rose-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-rose-700">Obat Stok Kritis</p>
            <p class="mt-2 text-2xl font-bold text-rose-600">{{ $stats['obat_kritis'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Tabel Resep Pasien --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Daftar Resep & Status Pelayanan Pasien
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Daftar resep dari dokter beserta status pelunasan di kasir sebelum obat diserahkan.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Kode Resep & Waktu</th>
                        <th class="px-5 py-4">Pasien & Poli</th>
                        <th class="px-5 py-4">Catatan Dokter</th>
                        <th class="px-5 py-4">Rincian Obat</th>
                        <th class="px-5 py-4">Status Kasir</th>
                        <th class="px-5 py-4">Status Resep</th>
                        <th class="px-5 py-4 text-center">Aksi Pelayanan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($reseps as $index => $resep)
                        @php
                            $tagihan = $resep->pendaftaran->tagihan ?? null;
                            $isLunas = $tagihan ? ($tagihan->status === 'lunas') : true;
                            $totalTagihan = $tagihan ? (float) $tagihan->total_tagihan : 0;
                            $sisaTagihan = $tagihan ? (float) $tagihan->sisa_tagihan : 0;
                        @endphp
                        <tr class="transition hover:bg-slate-50/80">
                            {{-- Nomor Urut --}}
                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            {{-- Kode Resep --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-mono text-xs font-semibold text-slate-800">
                                    {{ $resep->kode_resep }}
                                </span>
                                <p class="mt-0.5 text-xs text-slate-400">
                                    {{ $resep->created_at ? $resep->created_at->format('d/m/Y H:i') : '-' }}
                                </p>
                            </td>

                            {{-- Pasien & Poli --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">
                                    {{ $resep->pendaftaran->pasien->nama ?? 'Pasien Tidak Ditemukan' }}
                                </p>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    No. RM: <span class="font-mono text-slate-700">{{ $resep->pendaftaran->pasien->nomor_rm ?? '-' }}</span>
                                </p>
                                <span class="mt-1 inline-flex rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600">
                                    {{ $resep->pendaftaran->poli->nama_poli ?? 'Poli Umum' }}
                                </span>
                            </td>

                            {{-- Catatan Dokter --}}
                            <td class="px-5 py-4 text-slate-600">
                                <div class="max-w-[200px] whitespace-normal text-xs">
                                    {{ $resep->catatan_dokter ?: 'Tidak ada catatan khusus.' }}
                                </div>
                            </td>

                            {{-- Rincian Obat --}}
                            <td class="px-5 py-4">
                                <div class="min-w-[240px] space-y-2">
                                    @forelse($resep->detailReseps as $detail)
                                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-2.5 text-xs">
                                            <div class="flex items-center justify-between">
                                                <span class="font-semibold text-slate-800">
                                                    {{ $detail->obat->nama_obat ?? 'Obat tidak ditemukan' }}
                                                </span>
                                                <span class="font-medium text-slate-600">
                                                    {{ $detail->jumlah }} {{ $detail->obat->satuan ?? 'butir' }}
                                                </span>
                                            </div>

                                            <div class="mt-1 text-slate-500">
                                                <span>Dosis: {{ $detail->dosis }}</span>
                                                @if($detail->instruksi_khusus)
                                                    <span class="block text-slate-400 italic">{{ $detail->instruksi_khusus }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-xs text-slate-400">
                                            Belum ada resep obat.
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            {{-- Status Kasir --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                @if(!$tagihan)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                        Tanpa Tagihan
                                    </span>
                                @elseif($isLunas)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Lunas (Kasir)
                                    </span>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                                    </p>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        <svg class="h-3.5 w-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Belum Lunas
                                    </span>
                                    <p class="mt-1 text-xs text-amber-600 font-medium">
                                        Sisa: Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                                    </p>
                                @endif
                            </td>

                            {{-- Status Resep --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                @if($resep->status === 'selesai')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Selesai Diserahkan
                                    </span>
                                @elseif(in_array($resep->status, ['siap_diserahkan', 'diproses']))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-800">
                                        <svg class="h-3.5 w-3.5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        Siap Diserahkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        <svg class="h-3.5 w-3.5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Menunggu Kasir
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi Pelayanan --}}
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                @if($resep->status === 'selesai')
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-400">
                                        <svg class="h-4 w-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Selesai
                                    </span>
                                @elseif(!$isLunas)
                                    <div class="inline-flex flex-col items-center">
                                        <button type="button"
                                                disabled
                                                title="Pasien belum melunasi tagihan di kasir"
                                                class="inline-flex cursor-not-allowed items-center gap-1.5 rounded-lg border border-slate-200 bg-slate-100 px-3 py-1.5 text-xs font-medium text-slate-400 opacity-80">
                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            Terkunci Kasir
                                        </button>
                                        <span class="mt-1 text-[11px] text-amber-600">Menunggu Pelunasan</span>
                                    </div>
                                @else
                                    <div class="inline-flex flex-col items-center gap-1.5">
                                        <form action="{{ route('farmasi.updateStatus', $resep->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Konfirmasi penyerahan obat untuk pasien {{ $resep->pendaftaran->pasien->nama ?? '' }}?\n\nStok obat akan langsung dipotong dari inventaris secara otomatis.');">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="selesai">
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-3.5 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Serahkan Obat
                                            </button>
                                        </form>

                                        @if($resep->status === 'menunggu_pembayaran' || $resep->status === 'menunggu')
                                            <form action="{{ route('farmasi.updateStatus', $resep->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="diproses">
                                                <button type="submit" class="text-[11px] text-slate-500 hover:text-sky-600 underline">
                                                    Tandai Sedang Disiapkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <p class="mt-3 font-semibold text-slate-700">
                                    Belum ada resep masuk
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Resep yang dibuat oleh dokter akan langsung tampil pada antrean halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-5 py-3">
            <p class="text-xs text-slate-500">
                Menampilkan total {{ $reseps->count() }} data resep.
            </p>
        </div>
    </div>

</div>
@endsection