@extends('layouts.app')

@section('title', 'Manajemen Data Dokter - PT Oasis Husada')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Data Dokter</h1>
            <a href="{{ route('admin.dokter.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                + Tambah Dokter
            </a>
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
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Dokter</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Poli Utama</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nomor SIP</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">No Telepon</th>
                            <th class="px-6 py-3 bg-slate-50 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($dokters as $dokter)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $dokter->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $dokter->poli->nama_poli ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $dokter->nomor_sip ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">{{ $dokter->no_telepon ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.dokter.edit', $dokter->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.dokter.destroy', $dokter->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data dokter ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data dokter yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection