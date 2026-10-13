@extends('layouts.app')

@section('content')
<div class="py-12 bg-[#F3F7F9] min-h-screen">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8">
            
            <div class="mb-6">
                <h2 class="text-xl font-bold text-[#173D59]">Tambah Data Obat Baru</h2>
                <p class="text-sm text-gray-500">Masukkan informasi detail obat sesuai standar inventaris rumah sakit.</p>
            </div>

            {{-- Menampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('obat.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-[#173D59] mb-1">Kode Obat</label>
                    <input type="text" name="kode_obat" value="{{ old('kode_obat') }}" required 
                        class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]" placeholder="Contoh: OBT-001">
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#173D59] mb-1">Nama Obat</label>
                    <input type="text" name="nama_obat" value="{{ old('nama_obat') }}" required 
                        class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]" placeholder="Contoh: Paracetamol 500mg">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#173D59] mb-1">Satuan</label>
                        <input type="text" name="satuan" value="{{ old('satuan') }}" required 
                            class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]" placeholder="Contoh: Strip / Tablet">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#173D59] mb-1">Harga (Rp)</label>
                        <input type="number" step="0.01" name="harga" value="{{ old('harga') }}" required 
                            class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]" placeholder="Contoh: 5000">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-[#173D59] mb-1">Stok Awal</label>
                        <input type="number" name="stok" value="{{ old('stok', 0) }}" required 
                            class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]" placeholder="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#173D59] mb-1">Stok Minimum (Peringatan)</label>
                        <input type="number" name="stok_minimum" value="{{ old('stok_minimum', 10) }}" required 
                            class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]" placeholder="10">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[#173D59] mb-1">Status Aktif</label>
                    <select name="is_active" class="w-full border-gray-300 rounded-lg text-sm focus:border-[#17618B] focus:ring-[#17618B]">
                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('obat.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2 rounded-lg text-sm font-medium transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-[#17618B] hover:bg-[#124d6d] text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        Simpan Obat
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection