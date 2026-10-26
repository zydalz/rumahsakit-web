@extends('layouts.app')

@section('title', 'Pemeriksaan & Rekam Medis Pasien')
@section('header', 'Pemeriksaan Pasien Poli ' . ($pendaftaran->poli->nama_poli ?? ''))

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
            <p class="mb-2 font-semibold flex items-center gap-2">
                <svg class="h-4 w-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Periksa kembali data yang belum lengkap:
            </p>
            <ul class="list-inside list-disc space-y-1 text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Informasi Pasien & Peringatan Klinis --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-sky-700 bg-sky-50 px-2.5 py-1 rounded-md border border-sky-200">
                    No. Antrean #{{ str_pad($pendaftaran->nomor_antrean, 2, '0', STR_PAD_LEFT) }}
                </span>
                <h2 class="mt-2 text-xl font-bold text-slate-800">
                    {{ $pendaftaran->pasien->nama ?? $pendaftaran->pasien->user->name ?? 'Pasien' }}
                </h2>
                <p class="text-xs text-slate-500">
                    No. RM: <span class="font-mono font-semibold text-slate-700">{{ $pendaftaran->pasien->nomor_rm ?? '-' }}</span>
                    &bull; NIK: {{ $pendaftaran->pasien->nik ?? '-' }}
                </p>
            </div>

            <div class="text-left sm:text-right">
                <span class="text-xs font-semibold text-slate-500">Poli Tujuan:</span>
                <p class="font-bold text-slate-800">{{ $pendaftaran->poli->nama_poli ?? '-' }}</p>
                <p class="text-xs text-slate-500">Perkiraan Jam: {{ $pendaftaran->jam_estimasi ?: '08:00 - 09:00' }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                <span class="font-semibold text-slate-700">{{ $pendaftaran->pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-400 block mb-0.5">Tanggal Lahir / Usia</span>
                <span class="font-semibold text-slate-700">
                    {{ $pendaftaran->pasien->tanggal_lahir ? $pendaftaran->pasien->tanggal_lahir->format('d/m/Y') : '-' }}
                    ({{ $pendaftaran->pasien->tanggal_lahir ? $pendaftaran->pasien->tanggal_lahir->age . ' th' : '-' }})
                </span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-400 block mb-0.5">Penjamin</span>
                <span class="font-semibold text-slate-700">{{ $pendaftaran->jenis_penjamin }}</span>
            </div>
            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                <span class="text-slate-400 block mb-0.5">No. Telepon Pasien</span>
                <span class="font-semibold text-slate-700">{{ $pendaftaran->pasien->no_telepon ?? '-' }}</span>
            </div>
        </div>

        {{-- Kotak Peringatan Alergi & Keamanan Medis Pasien (Safety Alert) --}}
        <div class="rounded-xl border border-amber-200 bg-amber-50/80 p-4 text-xs text-amber-900 flex items-start gap-3">
            <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div>
                <strong class="font-semibold block text-amber-950 mb-0.5">Catatan Keselamatan Pasien & Alergi Obat:</strong>
                Pasien belum memiliki riwayat alergi obat berbahaya yang tercatat di sistem. Harap tetap konfirmasi secara lisan sebelum meresepkan antibiotik atau analgesik.
                @if($pendaftaran->keluhan_awal)
                    <div class="mt-1 text-slate-700">
                        <span class="font-semibold text-slate-800">Keluhan Awal Pasien:</span> "{{ $pendaftaran->keluhan_awal }}"
                    </div>
                @endif
            </div>
        </div>

        {{-- Riwayat Kunjungan Sebelumnya (Accordion / Collapse) --}}
        @if(isset($riwayatPemeriksaan) && $riwayatPemeriksaan->isNotEmpty())
            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 text-xs">
                <p class="font-bold text-slate-700 mb-2 flex items-center gap-1.5">
                    <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Riwayat Kunjungan & Diagnosis Terdahulu di RS Oasis Husada:
                </p>
                <div class="space-y-2">
                    @foreach($riwayatPemeriksaan as $riwayat)
                        <div class="bg-white p-2.5 rounded-lg border border-slate-200">
                            <div class="flex items-center justify-between text-slate-500">
                                <span>{{ $riwayat->created_at ? $riwayat->created_at->format('d/m/Y H:i') : '-' }} &bull; Dokter: {{ $riwayat->dokter->nama ?? '-' }}</span>
                            </div>
                            <p class="mt-1 font-semibold text-slate-800">Diagnosis: {{ $riwayat->diagnosis }}</p>
                            @if($riwayat->resep && $riwayat->resep->detailReseps->isNotEmpty())
                                <p class="text-slate-500 mt-0.5">
                                    Obat: {{ $riwayat->resep->detailReseps->pluck('obat.nama_obat')->filter()->join(', ') }}
                                </p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- Formulir Pemeriksaan Medis --}}
    <form id="form-pemeriksaan" method="POST" action="{{ route('dokter.pemeriksaan.store') }}" class="space-y-6">
        @csrf
        <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">

        {{-- Bagian 1: Hasil Pemeriksaan Fisik & Diagnosa --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <h3 class="mb-5 text-lg font-bold text-slate-800 flex items-center gap-2">
                <svg class="h-5 w-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Pemeriksaan Fisik & Diagnosa Klinis
            </h3>

            <div class="space-y-5">
                {{-- Keluhan Utama --}}
                <div>
                    <label for="keluhan" class="mb-1 block text-sm font-semibold text-slate-700">
                        Keluhan Utama Pasien <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <textarea id="keluhan" name="keluhan" rows="3" required
                              class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                              placeholder="Deskripsikan keluhan, gejala penyakit, dan durasi yang dirasakan pasien...">{{ old('keluhan', $pendaftaran->keluhan_awal) }}</textarea>
                </div>

                {{-- Tanda Vital --}}
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="tekanan_darah" class="mb-1 block text-xs font-semibold text-slate-700">
                            Tekanan Darah (mmHg) <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input id="tekanan_darah" name="tekanan_darah"
                               value="{{ old('tekanan_darah') }}"
                               placeholder="Contoh: 120/80"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    </div>

                    <div>
                        <label for="suhu" class="mb-1 block text-xs font-semibold text-slate-700">
                            Suhu Tubuh (°C) <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input id="suhu" name="suhu" type="number" step="0.1" min="30" max="45"
                               value="{{ old('suhu') }}"
                               placeholder="Contoh: 36.5"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    </div>

                    <div>
                        <label for="berat_badan" class="mb-1 block text-xs font-semibold text-slate-700">
                            Berat Badan (kg) <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input id="berat_badan" name="berat_badan" type="number" step="0.1" min="1" max="300"
                               value="{{ old('berat_badan') }}"
                               placeholder="Contoh: 65.0"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    </div>

                    <div>
                        <label for="tinggi_badan" class="mb-1 block text-xs font-semibold text-slate-700">
                            Tinggi Badan (cm) <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input id="tinggi_badan" name="tinggi_badan" type="number" step="0.1" min="30" max="250"
                               value="{{ old('tinggi_badan') }}"
                               placeholder="Contoh: 170"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    </div>
                </div>

                {{-- Diagnosis --}}
                <div>
                    <label for="diagnosis" class="mb-1 block text-sm font-semibold text-slate-700">
                        Diagnosis Medis <span class="text-rose-500 font-bold">*</span>
                    </label>
                    <textarea id="diagnosis" name="diagnosis" rows="3" required
                              class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                              placeholder="Masukkan diagnosis penyakit (contoh: ISPA, Hipertensi Grade 1, Dispepsia)...">{{ old('diagnosis') }}</textarea>
                </div>

                {{-- Catatan Tambahan Dokter --}}
                <div>
                    <label for="catatan" class="mb-1 block text-sm font-semibold text-slate-700">
                        Instruksi & Edukasi Medis Pasien
                    </label>
                    <textarea id="catatan" name="catatan" rows="2"
                              class="w-full rounded-xl border border-slate-300 px-3.5 py-2.5 text-sm outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                              placeholder="Pantangan makanan, anjuran istirahat, atau rencana kontrol berikutnya...">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- Bagian 2: Resep Obat Dinamis --}}
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <svg class="h-5 w-5 text-sky-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Resep Obat Pasien (Opsional)
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Dokter dapat memilih obat, mencari nama obat secara cepat, atau membatalkan/menghapus baris obat yang salah pilih.
                    </p>
                </div>

                <button type="button" id="tambah-obat"
                        class="inline-flex items-center gap-1.5 rounded-lg border border-sky-700 bg-sky-50 px-3.5 py-2 text-xs font-semibold text-sky-800 transition hover:bg-sky-100">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    + Tambah Obat Baru
                </button>
            </div>

            @if ($obats->isEmpty())
                <div class="rounded-xl bg-amber-50 p-4 text-xs text-amber-800 border border-amber-200">
                    Belum ada obat aktif dengan stok tersedia di farmasi. Pemeriksaan tetap dapat disimpan tanpa resep obat.
                </div>
            @else
                <div id="daftar-obat" class="space-y-4">
                    {{-- Baris Obat Pertama --}}
                    <div class="baris-obat relative rounded-xl border border-slate-200 bg-slate-50/70 p-4 transition hover:border-slate-300">
                        <div class="flex items-center justify-between border-b border-slate-200/80 pb-2 mb-3">
                            <span class="text-xs font-bold text-slate-600 flex items-center gap-1.5">
                                <span class="nomor-baris inline-flex h-5 w-5 items-center justify-center rounded-full bg-slate-200 text-[11px] font-bold text-slate-700">1</span>
                                Rincian Resep Obat
                            </span>
                            <button type="button" class="btn-hapus-obat text-xs text-rose-600 hover:text-rose-800 font-semibold inline-flex items-center gap-1 transition">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                Hapus Baris
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">
                                    Pilih Obat & Sisa Stok
                                </label>
                                <select name="obat[0][obat_id]"
                                        class="pilihan-obat w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                                    <option value="">-- Tidak Memilih Obat --</option>
                                    @foreach ($obats as $obat)
                                        <option value="{{ $obat->id }}" data-stok="{{ $obat->stok }}" data-satuan="{{ $obat->satuan }}"
                                            {{ (string) old('obat.0.obat_id') === (string) $obat->id ? 'selected' : '' }}>
                                            {{ $obat->nama_obat }} (Stok: {{ $obat->stok }} {{ $obat->satuan }}) - Rp {{ number_format($obat->harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">
                                    Dosis / Aturan Pakai
                                </label>
                                <input name="obat[0][dosis]"
                                       value="{{ old('obat.0.dosis') }}"
                                       placeholder="Contoh: 3x1 tablet sesudah makan"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">
                                    Jumlah Obat
                                </label>
                                <input type="number" min="1" max="1000" name="obat[0][jumlah]"
                                       value="{{ old('obat.0.jumlah') }}"
                                       placeholder="Contoh: 10"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                            </div>

                            <div>
                                <label class="mb-1 block text-xs font-medium text-slate-700">
                                    Instruksi Khusus / Tambahan
                                </label>
                                <input name="obat[0][instruksi_khusus]"
                                       value="{{ old('obat.0.instruksi_khusus') }}"
                                       placeholder="Contoh: Dihabiskan / Bila nyeri saja"
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Tombol Aksi Simpan & Batal --}}
        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 pt-2">
            <a href="{{ route('dokter.pemeriksaan.index') }}"
               class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-center text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                Kembali ke Antrean
            </a>

            <div class="flex items-center gap-3 justify-end">
                <button type="button" id="btn-buka-konfirmasi"
                        class="inline-flex items-center gap-2 rounded-xl bg-sky-700 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition hover:bg-sky-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Pemeriksaan & Resep
                </button>
            </div>
        </div>
    </form>
</div>

{{-- Modal Konfirmasi Sebelum Simpan Pemeriksaan --}}
<div id="modal-konfirmasi" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center gap-3 text-sky-800 mb-4">
            <div class="h-10 w-10 rounded-full bg-sky-100 flex items-center justify-center text-sky-700">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-800">Konfirmasi Simpan Rekam Medis</h3>
                <p class="text-xs text-slate-500">Pastikan seluruh data hasil pemeriksaan telah sesuai.</p>
            </div>
        </div>

        <div class="space-y-3 bg-slate-50 p-4 rounded-xl text-xs text-slate-700 border border-slate-200 mb-5">
            <div class="flex justify-between">
                <span class="text-slate-500">Pasien:</span>
                <strong class="text-slate-800">{{ $pendaftaran->pasien->nama ?? 'Pasien' }}</strong>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Nomor Antrean:</span>
                <strong class="text-slate-800">#{{ $pendaftaran->nomor_antrean }}</strong>
            </div>
            <div class="border-t border-slate-200 pt-2">
                <span class="text-slate-500 block mb-0.5">Ringkasan Diagnosis:</span>
                <p id="preview-diagnosis" class="font-medium text-slate-800 italic">-</p>
            </div>
            <div class="border-t border-slate-200 pt-2 text-[11px] text-sky-700">
                ℹ️ Saat disimpan, tagihan biaya dokter dan obat akan otomatis diterbitkan ke kasir dan resep akan dikirimkan ke farmasi.
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <button type="button" id="btn-tutup-modal"
                    class="rounded-lg border border-slate-300 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition">
                Periksa Kembali
            </button>
            <button type="button" id="btn-submit-final"
                    class="rounded-lg bg-sky-700 px-5 py-2 text-xs font-semibold text-white hover:bg-sky-800 transition">
                Ya, Simpan Permanen
            </button>
        </div>
    </div>
</div>

{{-- Script Resep Dinamis, Hapus Baris & Modal Konfirmasi --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const daftar = document.getElementById('daftar-obat');
    const tombolTambah = document.getElementById('tambah-obat');
    const modalKonfirmasi = document.getElementById('modal-konfirmasi');
    const btnBukaKonfirmasi = document.getElementById('btn-buka-konfirmasi');
    const btnTutupModal = document.getElementById('btn-tutup-modal');
    const btnSubmitFinal = document.getElementById('btn-submit-final');
    const formPemeriksaan = document.getElementById('form-pemeriksaan');
    const previewDiagnosis = document.getElementById('preview-diagnosis');

    // Fungsi perbarui nomor baris dan event hapus
    function refreshBarisObat() {
        if (!daftar) return;
        const semuaBaris = daftar.querySelectorAll('.baris-obat');
        semuaBaris.forEach((baris, i) => {
            const noBadge = baris.querySelector('.nomor-baris');
            if (noBadge) noBadge.textContent = i + 1;

            // Tombol hapus baris
            const btnHapus = baris.querySelector('.btn-hapus-obat');
            if (btnHapus) {
                btnHapus.onclick = function () {
                    if (semuaBaris.length > 1) {
                        baris.remove();
                        refreshBarisObat();
                    } else {
                        // Jika baris terakhir, kosongkan isiannya
                        baris.querySelectorAll('select, input').forEach(input => input.value = '');
                    }
                };
            }
        });
    }

    refreshBarisObat();

    // Tambah Baris Obat
    if (tombolTambah && daftar) {
        let currentIndex = daftar.querySelectorAll('.baris-obat').length;

        tombolTambah.addEventListener('click', function () {
            const template = daftar.querySelector('.baris-obat');
            if (!template) return;

            const barisBaru = template.cloneNode(true);
            barisBaru.querySelectorAll('select, input').forEach(function (field) {
                field.name = field.name.replace(/obat\[\d+\]/, 'obat[' + currentIndex + ']');
                field.value = '';
            });

            daftar.appendChild(barisBaru);
            currentIndex++;
            refreshBarisObat();
        });
    }

    // Modal Konfirmasi Simpan
    if (btnBukaKonfirmasi && modalKonfirmasi) {
        btnBukaKonfirmasi.addEventListener('click', function () {
            const keluhan = document.getElementById('keluhan').value.trim();
            const diagnosis = document.getElementById('diagnosis').value.trim();

            if (!keluhan) {
                alert('Silakan isi keluhan utama pasien terlebih dahulu.');
                document.getElementById('keluhan').focus();
                return;
            }

            if (!diagnosis) {
                alert('Silakan isi diagnosis medis pasien terlebih dahulu.');
                document.getElementById('diagnosis').focus();
                return;
            }

            if (previewDiagnosis) {
                previewDiagnosis.textContent = diagnosis;
            }

            modalKonfirmasi.classList.remove('hidden');
        });

        btnTutupModal.addEventListener('click', function () {
            modalKonfirmasi.classList.add('hidden');
        });

        btnSubmitFinal.addEventListener('click', function () {
            formPemeriksaan.submit();
        });
    }
});
</script>
@endsection
