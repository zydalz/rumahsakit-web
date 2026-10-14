@extends('layouts.app')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#173D59]">
            Daftar Tagihan
        </h1>
        <p class="mt-1 text-sm text-gray-600">
            Daftar tagihan pasien rumah sakit
        </p>
    </div>

    <a href="{{ route('tagihan.create') }}"
       class="inline-flex items-center justify-center rounded-lg bg-[#173D59] px-4 py-3 text-sm font-medium text-white hover:bg-[#245779]">
        + Tambah Tagihan
    </a>

    @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600">
                    <tr>
                        <th class="px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Nomor Tagihan</th>
                        <th class="px-5 py-4 font-semibold">ID Pendaftaran</th>
                        <th class="px-5 py-4 font-semibold">Total Tagihan</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse ($tagihans as $tagihan)
                        <tr class="hover:bg-gray-50">
                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $loop->iteration }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4 font-medium text-[#173D59]">
                                {{ $tagihan->nomor_tagihan }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                {{ $tagihan->pendaftaran_id }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                Rp{{ number_format($tagihan->total_tagihan, 0, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                @if ($tagihan->status === 'lunas')
                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                        Lunas
                                    </span>
                                @elseif ($tagihan->status === 'dibatalkan')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-5 py-4">
                                <a
                                    href="{{ route('tagihan.show', $tagihan->id) }}"
                                    class="inline-block rounded-lg bg-[#173D59] px-3 py-2 text-xs font-medium text-white hover:bg-[#245779]"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-medium text-gray-700">
                                    Belum ada data tagihan
                                </p>
                                <p class="mt-1 text-sm text-gray-500">
                                    Tagihan akan muncul di sini setelah data tersedia.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection