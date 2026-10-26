@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Riwayat Pendaftaran
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Lihat informasi dan status pendaftaran kunjunganmu.
            </p>
        </div>

        <a href="{{ route('pasien.pendaftaran.create') }}"
           class="inline-flex items-center justify-center rounded-lg bg-oasis-blue px-4 py-2.5 text-sm font-semibold text-white hover:bg-[#124D70]">
            + Buat Pendaftaran
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 p-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
        @if ($pendaftarans->isEmpty())
            <div class="px-6 py-12 text-center">
                <h2 class="font-semibold text-slate-700">
                    Belum ada pendaftaran
                </h2>
                <p class="mt-2 text-sm text-slate-500">
                    Pendaftaran yang kamu buat akan ditampilkan di halaman ini.
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Kode
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Tanggal Kunjungan
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Poli
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Antrean
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Status
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($pendaftarans as $pendaftaran)
                            <tr>
                                <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-slate-800">
                                    {{ $pendaftaran->kode_pendaftaran }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    <p class="font-medium text-slate-800">{{ $pendaftaran->tanggal_kunjungan->format('d-m-Y') }}</p>
                                    <p class="text-xs text-sky-700 font-semibold">{{ $pendaftaran->jam_estimasi ?? '-' }}</p>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    {{ $pendaftaran->poli->nama_poli ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm text-slate-600">
                                    <span class="inline-flex items-center rounded-md bg-sky-50 px-2.5 py-1 text-xs font-bold text-sky-700">
                                        #{{ $pendaftaran->nomor_antrean }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm">
                                    @if(in_array($pendaftaran->status, ['menunggu', 'menunggu_konfirmasi']))
                                        <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                            Menunggu Konfirmasi
                                        </span>
                                    @elseif($pendaftaran->status === 'dikonfirmasi')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-bold text-emerald-800">
                                            Dikonfirmasi
                                        </span>
                                    @elseif($pendaftaran->status === 'sedang_diperiksa')
                                        <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-800">
                                            Sedang Diperiksa
                                        </span>
                                    @elseif($pendaftaran->status === 'selesai')
                                        <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-bold text-slate-700">
                                            Selesai
                                        </span>
                                    @elseif($pendaftaran->status === 'dibatalkan')
                                        <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-0.5 text-xs font-bold text-rose-800">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-5 py-4 text-sm">
                                    <a href="{{ route('pasien.pendaftaran.show', $pendaftaran) }}"
                                       class="font-semibold text-oasis-blue hover:underline">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-5 py-4">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>
</div>
@endsection