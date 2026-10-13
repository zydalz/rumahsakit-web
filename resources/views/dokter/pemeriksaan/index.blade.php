@extends('layouts.app')

@section('title', 'Pemeriksaan Pasien - PT Oasis Husada')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 px-4 sm:px-0">
            <h1 class="text-2xl font-bold text-slate-800">Daftar Kunjungan & Pemeriksaan Pasien</h1>
            <p class="text-sm text-slate-500">Poli: {{ $dokter->poli->nama_poli ?? '-' }}</p>
        </div>

        @if(session('success'))
            <div class="mb-6 mx-4 sm:mx-0 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg shadow-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-slate-200">
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No Antrean</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Pendaftaran</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Pasien</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal Kunjungan</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 bg-slate-50 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($pendaftarans as $pendaftaran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap font-extrabold text-blue-600 text-base">#{{ $pendaftaran->nomor_antrean }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $pendaftaran->kode_pendaftaran }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">{{ $pendaftaran->pasien->user->name ?? 'Pasien' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $pendaftaran->tanggal_kunjungan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full 
                                        {{ $pendaftaran->status == 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($pendaftaran->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    @if($pendaftaran->pemeriksaan)
                                        <a href="{{ route('dokter.pemeriksaan.show', $pendaftaran->pemeriksaan->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Lihat Rekam Medis</a>
                                    @else
                                        <a href="{{ route('dokter.pemeriksaan.create', $pendaftaran->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-semibold shadow transition">Periksa Pasien</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data kunjungan pasien di poli ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection