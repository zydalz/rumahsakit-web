@extends('layouts.app')

@section('title', 'Manajemen Data Poli - PT Oasis Husada')

@section('content')
<div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center px-4 sm:px-0">
            <h1 class="text-2xl font-bold text-slate-800">Manajemen Data Poli</h1>
            <a href="{{ route('admin.poli.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow transition">
                + Tambah Poli
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
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Poli</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 bg-slate-50 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 bg-slate-50 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @forelse($polis as $poli)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900">{{ $poli->nama_poli }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $poli->deskripsi ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full {{ $poli->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $poli->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('admin.poli.edit', $poli->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                    <form action="{{ route('admin.poli.destroy', $poli->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus data poli ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-900 font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data poli yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection