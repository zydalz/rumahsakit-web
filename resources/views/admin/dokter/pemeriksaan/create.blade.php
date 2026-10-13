@extends('layouts.app')

@section('title', 'Input Pemeriksaan Pasien - PT Oasis Husada')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header / Navigation Back -->
        <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Form Pemeriksaan Pasien</h1>
                <p class="text-sm text-slate-500">Kode Pendaftaran: <span class="font-semibold text-blue-600">{{ $pendaftaran->kode_pendaftaran }}</span></p>
            </div>
            <a href="{{ route('dokter.pemeriksaan.index') }}" class="text-sm text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1">
                &larr; Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('dokter.pemeriksaan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">

            <!-- Ringkasan Identitas Pasien -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm mb-6">
                <h2 class="text-base font-bold text-slate-900 mb-4 border-b border-slate-100 pb-2">Informasi Pasien</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-slate-700">
                    <div>
                        <span class="block text-slate-400 text-xs font-semibold">NAMA PASIEN</span>
                        <span class="font-bold text-slate-900">{{ $pendaftaran->pasien->user->name ?? 'Pasien' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-xs font-semibold">NOMOR RM</span>
                        <span class="font-semibold text-slate-800">{{ $pendaftaran->pasien->nomor_rm ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-400 text-xs font-semibold">NO ANTREAN</span>
                        <span class="font-extrabold text-blue-600">#{{ $pendaftaran->nomor_antrean }}</span>
                    </div>
                </div>
            </div>

            <!-- Card Form Pemeriksaan -->
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2">Pemeriksaan Fisik & Vital Signs</h2>

                <!-- Vital Signs Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tekanan Darah (mmHg)</label>
                        <input type="text" name="tekanan_darah" value="{{ old('tekanan_darah') }}" placeholder="Contoh: 120/80"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Suhu (°C)</label>
                        <input type="number" step="0.1" name="suhu" value="{{ old('suhu') }}" placeholder="Contoh: 36.5"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan') }}" placeholder="Contoh: 65.5"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan') }}" placeholder="Contoh: 170"
                            class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>

                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-2 pt-2">Hasil Anamnesis & Klinis</h2>

                <!-- Keluhan Utama -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Keluhan Utama <span class="text-rose-500">*</span></label>
                    <textarea name="keluhan" rows="3" required placeholder="Jelaskan keluhan utama pasien..."
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('keluhan', $pendaftaran->keluhan_awal) }}</textarea>
                </div>

                <!-- Diagnosis -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Diagnosis Dokter <span class="text-rose-500">*</span></label>
                    <textarea name="diagnosis" rows="3" required placeholder="Tuliskan diagnosis klinis pasien (misal: Febris, Dyspepsia, dll)..."
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('diagnosis') }}</textarea>
                </div>

                <!-- Catatan & Resep Obat -->
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan Dokter & Anjuran/Resep</label>
                    <textarea name="catatan" rows="3" placeholder="Tambahkan instruksi resep obat, edukasi, atau catatan lanjutan..."
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500">{{ old('catatan') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 flex justify-end gap-3">
                    <a href="{{ route('dokter.pemeriksaan.index') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-600 hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 shadow-md transition">
                        Simpan Rekam Medis
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection