@extends('layouts.app')

@section('title', 'Detail Rekam Medis - PT Oasis Husada')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header / Navigation Back -->
        <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Detail Rekam Medis Pasien</h1>
                <p class="text-sm text-slate-500">Tanggal Pemeriksaan: {{ $pemeriksaan->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <a href="{{ route('dokter.pemeriksaan.index') }}" class="text-sm text-slate-600 hover:text-slate-900 font-semibold flex items-center gap-1">
                &larr; Kembali ke List
            </a>
        </div>

        <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm space-y-6">
            <!-- Informasi Pasien & Dokter -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-slate-100 pb-6">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Data Pasien</h3>
                    <p class="text-lg font-bold text-slate-900">{{ $pemeriksaan->pendaftaran->pasien->user->name ?? 'Pasien' }}</p>
                    <p class="text-sm text-slate-600">No RM: {{ $pemeriksaan->pendaftaran->pasien->nomor_rm ?? '-' }}</p>
                    <p class="text-sm text-slate-600">Kode Pendaftaran: {{ $pemeriksaan->pendaftaran->kode_pendaftaran }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Pemeriksa</h3>
                    <p class="text-lg font-bold text-slate-900">Dr. {{ $pemeriksaan->dokter->user->name ?? '-' }}</p>
                    <p class="text-sm text-slate-600">SIP: {{ $pemeriksaan->dokter->nomor_sip ?? '-' }}</p>
                    <p class="text-sm text-slate-600">Poli: {{ $pemeriksaan->dokter->poli->nama_poli ?? '-' }}</p>
                </div>
            </div>

            <!-- Vital Signs -->
            <div>
                <h3 class="text-sm font-bold text-slate-800 mb-3">Tanda-tanda Vital</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <span class="block text-xs text-slate-400 font-semibold">Tekanan Darah</span>
                        <span class="text-base font-bold text-slate-800">{{ $pemeriksaan->tekanan_darah ?? '-' }} mmHg</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <span class="block text-xs text-slate-400 font-semibold">Suhu Tubuh</span>
                        <span class="text-base font-bold text-slate-800">{{ $pemeriksaan->suhu ? $pemeriksaan->suhu . ' °C' : '-' }}</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <span class="block text-xs text-slate-400 font-semibold">Berat Badan</span>
                        <span class="text-base font-bold text-slate-800">{{ $pemeriksaan->berat_badan ? $pemeriksaan->berat_badan . ' kg' : '-' }}</span>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-lg border border-slate-100">
                        <span class="block text-xs text-slate-400 font-semibold">Tinggi Badan</span>
                        <span class="text-base font-bold text-slate-800">{{ $pemeriksaan->tinggi_badan ? $pemeriksaan->tinggi_badan . ' cm' : '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Hasil Klinis -->
            <div class="space-y-4 border-t border-slate-100 pt-4">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Keluhan Utama</h3>
                    <p class="text-sm text-slate-800 bg-slate-50 p-3 rounded-lg border border-slate-100">{{ $pemeriksaan->keluhan }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Diagnosis Dokter</h3>
                    <p class="text-sm font-semibold text-blue-900 bg-blue-50/50 p-3 rounded-lg border border-blue-100">{{ $pemeriksaan->diagnosis }}</p>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan & Anjuran/Resep</h3>
                    <p class="text-sm text-slate-800 bg-slate-50 p-3 rounded-lg border border-slate-100">{{ $pemeriksaan->catatan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection