@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#F3F7F9] min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-[#173D59] leading-tight">
                {{ __('Manajemen Master Obat') }}
            </h2>
            <a href="{{ route('farmasi.obat.create') }}" class="bg-[#17618B] hover:bg-[#124d6d] text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                + Tambah Obat Baru
            </a>
        </div>

        {{-- Notifikasi Sukses --}}
        @if (session('success'))
            <div class="mb-4 bg-[#087C78]/10 border border-[#087C78] text-[#087C78] px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-6">
            
            {{-- Form Pencarian --}}
            <form method="GET" action="{{ route('farmasi.obat.index') }}" class="mb-6 flex gap-4">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari berdasarkan kode atau nama obat..." 
                    class="w-full sm:w-1/3 border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]">
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white px-5 py-2 rounded-lg text-sm font-medium transition">
                    Cari
                </button>
                @if(isset($search))
                    <a href="{{ route('farmasi.obat.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
                        Reset
                    </a>
                @endif
            </form>

            {{-- Tabel Data Obat --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-sm">
                    <thead class="bg-[#F3F7F9] text-[#173D59] font-semibold">
                        <tr>
                            <th class="px-6 py-3">Kode</th>
                            <th class="px-6 py-3">Nama Obat</th>
                            <th class="px-6 py-3">Satuan</th>
                            <th class="px-6 py-3">Harga (Rp)</th>
                            <th class="px-6 py-3">Stok</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-gray-700">
                        @forelse ($obats as $obat)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-[#173D59]">{{ $obat->kode_obat }}</td>
                                <td class="px-6 py-4">{{ $obat->nama_obat }}</td>
                                <td class="px-6 py-4">{{ $obat->satuan }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($obat->harga, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <span class="{{ $obat->stok <= $obat->stok_minimum ? 'text-red-600 font-bold' : '' }}">
                                        {{ $obat->stok }}
                                    </span>
                                    @if($obat->stok <= $obat->stok_minimum)
                                        <span class="text-xs bg-red-100 text-red-700 px-1.5 py-0.5 rounded ml-1">Min: {{ $obat->stok_minimum }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($obat->is_active)
                                        <span class="bg-[#087C78]/10 text-[#087C78] px-2.5 py-1 rounded-full text-xs font-semibold">Aktif</span>
                                    @else
                                        <span class="bg-red-100 text-red-600 px-2.5 py-1 rounded-full text-xs font-semibold">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center space-x-2">
                                    <a href="{{ route('farmasi.obat.edit', $obat->id) }}" class="text-[#17618B] hover:underline font-medium">Edit</a>
                                    <form action="{{ route('farmasi.obat.destroy', $obat->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus obat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Belum ada data obat yang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginasi --}}
            <div class="mt-4">
                {{ $obats->links() }}
            </div>

        </div>
    </div>
</div>
@endsection