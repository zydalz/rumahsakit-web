<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Pemeriksaan Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-6 border-b pb-4">
                    <p class="text-sm text-gray-600"><strong>Kode Pendaftaran:</strong> {{ $pendaftaran->kode_pendaftaran }}</p>
                    <p class="text-sm text-gray-600"><strong>Nama Pasien:</strong> {{ $pendaftaran->pasien->user->name ?? '-' }}</p>
                    <p class="text-sm text-gray-600"><strong>Nomor RM:</strong> {{ $pendaftaran->pasien->nomor_rm ?? '-' }}</p>
                </div>

                <form action="{{ route('dokter.pemeriksaan.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pendaftaran_id" value="{{ $pendaftaran->id }}">
                    <input type="hidden" name="dokter_id" value="{{ $dokter->id }}">

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Keluhan Utama *</label>
                        <textarea name="keluhan" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>{{ old('keluhan', $pendaftaran->keluhan_awal) }}</textarea>
                        @error('keluhan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tekanan Darah (mmHg)</label>
                            <input type="text" name="tekanan_darah" placeholder="Contoh: 120/80" value="{{ old('tekanan_darah') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Suhu Tubuh (°C)</label>
                            <input type="number" step="0.1" name="suhu" placeholder="Contoh: 36.5" value="{{ old('suhu') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Berat Badan (kg)</label>
                            <input type="number" step="0.01" name="berat_badan" placeholder="Contoh: 65.5" value="{{ old('berat_badan') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tinggi Badan (cm)</label>
                            <input type="number" step="0.01" name="tinggi_badan" placeholder="Contoh: 170" value="{{ old('tinggi_badan') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Diagnosis Klinis *</label>
                        <textarea name="diagnosis" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight" required>{{ old('diagnosis') }}</textarea>
                        @error('diagnosis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex items-center justify-end">
                        <a href="{{ route('dokter.pemeriksaan.index') }}" class="text-gray-600 mr-4 text-sm">Batal</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Simpan Pemeriksaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>