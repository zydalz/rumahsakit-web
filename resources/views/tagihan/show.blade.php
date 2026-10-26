@extends('layouts.app')

@section('content')
@php
    $totalTagihan = (float) $tagihan->total_tagihan;

    $totalDibayar = (float) $tagihan->pembayaran
        ->where('status', 'berhasil')
        ->sum('jumlah_bayar');

    $sisaTagihan = max(0, $totalTagihan - $totalDibayar);
@endphp

<div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Detail Tagihan & Pembayaran
            </h1>
            <p class="text-sm text-gray-600">
                Nomor Tagihan: #{{ $tagihan->nomor_tagihan ?? $tagihan->id }}
            </p>
        </div>

        <a href="{{ route('tagihan.index') }}"
           class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 text-sm font-medium">
            &larr; Kembali
        </a>
    </div>

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

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded-lg">
            <p class="font-semibold mb-1">Pembayaran belum berhasil disimpan.</p>
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Ringkasan Tagihan --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">
                    Informasi Tagihan
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <span class="text-gray-500 block">Total Tagihan</span>
                        <span class="text-xl font-bold text-gray-900">
                            Rp {{ number_format($totalTagihan, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500 block">Status Pembayaran</span>
                        <span class="mt-1 inline-block px-3 py-1 text-xs font-semibold rounded-full
                            {{ $tagihan->status === 'lunas'
                                ? 'bg-green-100 text-green-800'
                                : ($tagihan->status === 'dibatalkan'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                            {{ strtoupper(str_replace('_', ' ', $tagihan->status)) }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500 block">Total Dibayar</span>
                        <span class="text-lg font-semibold text-green-700">
                            Rp {{ number_format($totalDibayar, 0, ',', '.') }}
                        </span>
                    </div>

                    <div>
                        <span class="text-gray-500 block">Sisa Tagihan</span>
                        <span class="text-lg font-bold {{ $sisaTagihan > 0 ? 'text-red-600' : 'text-green-700' }}">
                            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Rincian Tagihan --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">
                    Rincian Tagihan
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-500">
                                <th class="px-4 py-3">Deskripsi</th>
                                <th class="px-4 py-3 text-center">Jumlah</th>
                                <th class="px-4 py-3 text-right">Harga Satuan</th>
                                <th class="px-4 py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($tagihan->detailTagihan as $detail)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ $detail->deskripsi }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        {{ $detail->jumlah }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">
                                        Belum ada rincian tagihan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Riwayat Pembayaran --}}
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">
                    Riwayat Pembayaran Kasir
                </h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-left text-gray-500">
                                <th class="px-4 py-2">Tanggal</th>
                                <th class="px-4 py-2">Metode</th>
                                <th class="px-4 py-2">Jumlah Bayar</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Nota</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($tagihan->pembayaran as $bayar)
                                <tr>
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                        {{ \Illuminate\Support\Carbon::parse($bayar->tanggal_bayar)->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-4 py-3 text-gray-700 uppercase font-medium">
                                        {{ $bayar->metode }}
                                    </td>

                                    <td class="px-4 py-3 font-semibold text-green-700">
                                        Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded font-medium
                                            {{ $bayar->status === 'berhasil'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($bayar->status) }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($bayar->status === 'berhasil')
                                            <a
                                                href="{{ route('tagihan.pembayaran.nota', [
                                                    'tagihan' => $tagihan->id,
                                                    'pembayaran' => $bayar->id,
                                                ]) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-block rounded bg-[#173D59] px-3 py-2 text-xs font-medium text-white hover:bg-[#245779]"
                                            >
                                                Cetak Nota
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500 italic">
                                        Belum ada riwayat transaksi pembayaran tercatat.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6 sticky top-6">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-3 mb-4">
                    Input Pembayaran Kasir
                </h3>

                @if($tagihan->status === 'lunas')
                    <div class="p-4 bg-green-50 text-green-800 rounded-lg text-center font-medium text-sm">
                        Tagihan ini sudah lunas sepenuhnya.
                    </div>
                @elseif($tagihan->status === 'dibatalkan')
                    <div class="p-4 bg-red-50 text-red-800 rounded-lg text-center font-medium text-sm">
                        Tagihan dibatalkan dan tidak dapat menerima pembayaran.
                    </div>
                @else
                    <div class="mb-4 p-3 bg-blue-50 text-blue-800 rounded-lg text-sm">
                        Sisa yang harus dibayar:
                        <span class="block text-lg font-bold">
                            Rp {{ number_format($sisaTagihan, 0, ',', '.') }}
                        </span>
                    </div>

                    <form action="{{ route('tagihan.pembayaran.store', $tagihan->getKey()) }}"
                          method="POST"
                          class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Jumlah Bayar (Rp)
                            </label>
                            <input
                                type="number"
                                name="jumlah_bayar"
                                min="0.01"
                                max="{{ $sisaTagihan }}"
                                step="0.01"
                                value="{{ old('jumlah_bayar') }}"
                                required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2"
                                placeholder="Masukkan jumlah pembayaran">

                            @error('jumlah_bayar')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Metode Pembayaran
                            </label>
                            <select name="metode" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2">
                                <option value="tunai" {{ old('metode') === 'tunai' ? 'selected' : '' }}>
                                    Tunai
                                </option>
                                <option value="transfer" {{ old('metode') === 'transfer' ? 'selected' : '' }}>
                                    Transfer Bank
                                </option>
                                <option value="qris" {{ old('metode') === 'qris' ? 'selected' : '' }}>
                                    QRIS
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                Referensi / No. Bukti (Opsional)
                            </label>
                            <input
                                type="text"
                                name="referensi"
                                value="{{ old('referensi') }}"
                                maxlength="100"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border px-3 py-2"
                                placeholder="Nomor referensi atau catatan">
                        </div>

                        <button type="submit"
                                class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 font-medium text-sm transition">
                            Simpan Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection