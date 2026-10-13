<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Rekam Medis
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="rounded-lg bg-white p-6 shadow-sm">
                <h3 class="mb-4 text-lg font-bold text-gray-800">
                    Informasi Pasien
                </h3>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p class="text-sm text-gray-500">Nama Pasien</p>
                        <p class="font-medium">
                            {{ $pemeriksaan->pendaftaran->pasien->nama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nomor Rekam Medis</p>
                        <p class="font-medium">
                            {{ $pemeriksaan->pendaftaran->pasien->nomor_rm ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Kode Pendaftaran</p>
                        <p class="font-medium">
                            {{ $pemeriksaan->pendaftaran->kode_pendaftaran }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Kunjungan</p>
                        <p class="font-medium">
                            {{ $pemeriksaan->pendaftaran->tanggal_kunjungan->format('d-m-Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Dokter</p>
                        <p class="font-medium">
                            {{ $pemeriksaan->dokter->user->name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Poli</p>
                        <p class="font-medium">
                            {{ $pemeriksaan->dokter->poli->nama_poli ?? '-' }}
                        </p>
                    </div>
                </div>

                <hr class="my-6">

                <h3 class="mb-4 text-lg font-bold text-gray-800">
                    Hasil Pemeriksaan
                </h3>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Keluhan Utama</p>
                        <p class="whitespace-pre-line">
                            {{ $pemeriksaan->keluhan }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Tekanan Darah</p>
                            <p>{{ $pemeriksaan->tekanan_darah ?? '-' }} mmHg</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Suhu Tubuh</p>
                            <p>{{ $pemeriksaan->suhu ?? '-' }} °C</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Berat Badan</p>
                            <p>{{ $pemeriksaan->berat_badan ?? '-' }} kg</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Tinggi Badan</p>
                            <p>{{ $pemeriksaan->tinggi_badan ?? '-' }} cm</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Diagnosis</p>
                        <p class="whitespace-pre-line">
                            {{ $pemeriksaan->diagnosis }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Catatan Tambahan</p>
                        <p class="whitespace-pre-line">
                            {{ $pemeriksaan->catatan ?? '-' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('dokter.pemeriksaan.index') }}"
                       class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                        Kembali ke Daftar Pemeriksaan
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>