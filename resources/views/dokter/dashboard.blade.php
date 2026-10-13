@extends('layouts.app')

@section('title', 'Dashboard Dokter - PT Oasis Husada')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 px-4 sm:px-0">
            <h1 class="text-2xl font-bold text-slate-800">Dashboard Dokter</h1>
            <p class="text-sm text-slate-500">Selamat datang kembali, Dr. {{ Auth::user()->name }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 px-4 sm:px-0">
            <!-- Card Antrean Hari Ini -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-blue-600 border-slate-200 border">
                <div class="text-slate-500 text-sm font-semibold">Antrean Pasien Hari Ini</div>
                <div class="text-4xl font-extrabold text-slate-900 mt-2">{{ $antreanHariIni }}</div>
                <div class="text-xs text-slate-400 mt-1">Poli: {{ $dokter->poli->nama_poli ?? '-' }}</div>
            </div>

            <!-- Card Total Pasien Diperiksa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-emerald-600 border-slate-200 border">
                <div class="text-slate-500 text-sm font-semibold">Total Pasien Telah Diperiksa</div>
                <div class="text-4xl font-extrabold text-slate-900 mt-2">{{ $totalPasienDiperiksa }}</div>
                <div class="text-xs text-slate-400 mt-1">Akumulasi riwayat pemeriksaan klinis</div>
            </div>
        </div>

        <!-- Informasi Profil Praktik -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border border-slate-200 mx-4 sm:mx-0">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Informasi Praktik Dokter</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-slate-600">
                <p><strong>Nama Lengkap:</strong> Dr. {{ Auth::user()->name }}</p>
                <p><strong>Nomor SIP:</strong> {{ $dokter->nomor_sip ?? 'Belum diatur' }}</p>
                <p><strong>Poli Utama:</strong> {{ $dokter->poli->nama_poli ?? '-' }}</p>
                <p><strong>No Telepon:</strong> {{ $dokter->no_telepon ?? '-' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection