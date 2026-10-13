@extends('layouts.app')

@section('content')
<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Detail Tagihan & Pembayaran</h1>
            <p class="text-sm text-gray-600">Nomor Tagihan: #{{ $tagihan->nomor_tagihan ?? $tagihan->id }}</p>
        </div>
        <a href="{{ route('tagihan.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">
            &larr; Kembali
        </a>
    </div>

    {{-- Notifikasi Sukses / Gagal --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Kolom Kiri: Ringkasan Tagihan & Riwayat Pembayaran --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Informasi Tagihan</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500 block">Total Tagihan</span>
                        <span class="text-xl font-bold text-gray-900">Rp {{ number_format($tagihan->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 block">Status Pembayaran</span>
                        <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full {{ $tagihan->status === 'lunas' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ strtoupper($tagihan->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Riwayat Pembayaran Kasir</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-500 font-medium">
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Metode</th>
                                <th class="px-4 py-2">Jumlah Bayar</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($tagihan->pembayaran as $bayar)
                                <tr>
                                    <td class="px-4 py-3">{{ $bayar->tanggal_bayar }}</td>
                                    <td class="px-4 py-3 uppercase font-medium text-gray-700">{{ $bayar->metode }}</td>
                                    <td class="px-4 py-3 font-semibold text-green-600">Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Berhasil</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-center text-gray-500 italic">Belum ada transaksi pembayaran tercatat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Form Input Kasir --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">Input Pembayaran Kasir</h3>
                
                @if($tagihan->status === 'lunas')
                    <div class="p-4 bg-green-50 text-green-800 rounded-lg text-center font-medium text-sm">
                        Tagihan ini sudah lunas sepenuhnya.
                    </div>
                @else
                    <form action="{{ route('tagihan.pembayaran.store', $tagihan->getKey()) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Bayar (Rp)</label>
                            <input type="number" name="jumlah_bayar" step="0.01" required 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2"
                                   placeholder="Contoh: 100000">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
                            <select name="metode" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                                <option value="tunai">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="qris">QRIS</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Referensi / No. Bukti (Opsional)</label>
                            <input type="text" name="referensi" 
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2"
                                   placeholder="No. Referensi / Catatan">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 font-medium text-sm transition">
                            Simpan Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection