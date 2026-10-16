@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien')
@section('header', 'Pemeriksaan Pasien')

@section('content')
<div class="mx-auto max-w-5xl space-y-6">

    @if ($errors->any())
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <p class="mb-2 font-semibold">Periksa kembali data berikut:</p>
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="mb-4 text-lg font-bold text-slate-800">
            Informasi Pasien
        </h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <p class="text-sm text-slate-500">Nama Pasien</p>
                <p class="font-medium text-slate-800">
                    {{ $pendaftaran->pasien->nama
                        ?? $pendaftaran->pasien->user->name
                        ?? 'Pasien' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Nomor Rekam Medis</p>
                <p class="font-medium text-slate-800">
                    {{ $pendaftaran->pasien->nomor_rm ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Kode Pendaftaran</p>
                <p class="font-medium text-slate-800">
                    {{ $pendaftaran->kode_pendaftaran }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">Poli</p>
                <p class="font-medium text-slate-800">
                    {{ $pendaftaran->poli->nama_poli ?? '-' }}
                </p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('dokter.pemeriksaan.store') }}"
        class="space-y-6">
        @csrf

        <input type="hidden" name="pendaftaran_id"
            value="{{ $pendaftaran->id }}">

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-5 text-lg font-bold text-slate-800">
                Hasil Pemeriksaan
            </h2>

            <div class="space-y-5">
                <div>
                    <label for="keluhan"
                        class="mb-1 block text-sm font-medium text-slate-700">
                        Keluhan Utama <span class="text-red-500">*</span>
                    </label>
                    <textarea id="keluhan" name="keluhan" rows="3" required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                        placeholder="Masukkan keluhan pasien">{{ old('keluhan') }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="tekanan_darah"
                            class="mb-1 block text-sm font-medium text-slate-700">
                            Tekanan Darah (mmHg)
                        </label>
                        <input id="tekanan_darah" name="tekanan_darah"
                            value="{{ old('tekanan_darah') }}"
                            placeholder="Contoh: 120/80"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label for="suhu"
                            class="mb-1 block text-sm font-medium text-slate-700">
                            Suhu Tubuh (°C)
                        </label>
                        <input id="suhu" name="suhu" type="number"
                            step="0.1" min="30" max="45"
                            value="{{ old('suhu') }}"
                            placeholder="Contoh: 36.5"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label for="berat_badan"
                            class="mb-1 block text-sm font-medium text-slate-700">
                            Berat Badan (kg)
                        </label>
                        <input id="berat_badan" name="berat_badan"
                            type="number" step="0.1" min="1" max="300"
                            value="{{ old('berat_badan') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>

                    <div>
                        <label for="tinggi_badan"
                            class="mb-1 block text-sm font-medium text-slate-700">
                            Tinggi Badan (cm)
                        </label>
                        <input id="tinggi_badan" name="tinggi_badan"
                            type="number" step="0.1" min="30" max="250"
                            value="{{ old('tinggi_badan') }}"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2">
                    </div>
                </div>

                <div>
                    <label for="diagnosis"
                        class="mb-1 block text-sm font-medium text-slate-700">
                        Diagnosis <span class="text-red-500">*</span>
                    </label>
                    <textarea id="diagnosis" name="diagnosis" rows="3"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                        placeholder="Masukkan diagnosis">{{ old('diagnosis') }}</textarea>
                </div>

                <div>
                    <label for="catatan"
                        class="mb-1 block text-sm font-medium text-slate-700">
                        Catatan Tambahan
                    </label>
                    <textarea id="catatan" name="catatan" rows="3"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2"
                        placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="mb-2 text-lg font-bold text-slate-800">
                Resep Obat
            </h2>
            <p class="mb-5 text-sm text-slate-500">
                Pengisian resep bersifat opsional. Pilih obat jika pasien
                memerlukan resep.
            </p>

            @if ($obats->isEmpty())
                <p class="rounded-lg bg-amber-50 p-3 text-sm text-amber-800">
                    Belum ada obat aktif dengan stok tersedia.
                    Pemeriksaan tetap dapat disimpan tanpa resep.
                </p>
            @else
                <div id="daftar-obat" class="space-y-4">
                    <div class="baris-obat grid grid-cols-1 gap-4 rounded-lg bg-slate-50 p-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Obat
                            </label>
                            <select name="obat[0][obat_id]"
                                class="pilihan-obat w-full rounded-lg border border-slate-300 px-3 py-2">
                                <option value="">Tidak memilih obat</option>
                                @foreach ($obats as $obat)
                                    <option value="{{ $obat->id }}"
                                        {{ (string) old('obat.0.obat_id') === (string) $obat->id ? 'selected' : '' }}>
                                        {{ $obat->nama_obat }}
                                        (stok: {{ $obat->stok }} {{ $obat->satuan }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        
                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Dosis / Aturan Pakai
                            </label>
                            <input
                                name="obat[0][dosis]"
                                value="{{ old('obat.0.dosis') }}"
                                placeholder="Contoh: 1 kapsul, 3 kali sehari"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2"
                            >
                            <p class="mt-1 text-xs text-slate-500">
                                Isi aturan pemakaian sesuai instruksi dokter.
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Jumlah Obat
                            </label>
                            <input
                                type="number"
                                min="1"
                                max="1000"
                                name="obat[0][jumlah]"
                                value="{{ old('obat.0.jumlah') }}"
                                placeholder="Contoh: 90"
                                required
                                class="w-full rounded-lg border border-slate-300 px-3 py-2"
                            >
                            <p class="mt-1 text-xs text-slate-500">
                                Isi total obat yang diresepkan.
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-slate-700">
                                Instruksi Khusus
                            </label>
                            <input
                                name="obat[0][instruksi_khusus]"
                                value="{{ old('obat.0.instruksi_khusus') }}"
                                placeholder="Opsional, sesuai instruksi dokter"
                                maxlength="2000"
                                class="w-full rounded-lg border border-slate-300 px-3 py-2"
                            >
                        </div>
                    </div>
                </div>

                <button type="button" id="tambah-obat"
                    class="mt-4 rounded-lg border border-sky-700 px-4 py-2 text-sm font-semibold text-sky-700 hover:bg-sky-50">
                    + Tambah Obat
                </button>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const daftar = document.getElementById('daftar-obat');
                        const tombol = document.getElementById('tambah-obat');

                        if (!daftar || !tombol) return;

                        let index = 1;

                        tombol.addEventListener('click', function () {
                            const baris = daftar.querySelector('.baris-obat').cloneNode(true);

                            baris.querySelectorAll('select, input').forEach(function (field) {
                                field.name = field.name.replace(
                                    /obat\[\d+\]/,
                                    'obat[' + index + ']'
                                );
                                field.value = '';
                            });

                            daftar.appendChild(baris);
                            index++;
                        });
                    });
                </script>
            @endif
        </div>

        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a href="{{ route('dokter.pemeriksaan.index') }}"
                class="rounded-lg border border-slate-300 px-5 py-2.5 text-center text-sm font-semibold text-slate-700 hover:bg-slate-50">
                Batal
            </a>

            <button type="submit"
                class="rounded-lg bg-sky-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-sky-800">
                Simpan Pemeriksaan
            </button>
        </div>
    </form>
</div>
@endsection
