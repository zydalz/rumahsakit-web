@extends('layouts.app')

@section('title', 'Dashboard Farmasi')
@section('header', 'Dashboard Farmasi')

@section('content')
<div class="space-y-6">

    {{-- Ringkasan --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Daftar Resep
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola resep dan informasi obat pasien.
            </p>
        </div>

        <div class="rounded-xl border border-sky-100 bg-white px-5 py-4 shadow-sm">
            <p class="text-sm text-slate-500">Total Resep</p>
            <p class="mt-1 text-2xl font-bold text-sky-700">
                {{ $reseps->count() }}
            </p>
        </div>
    </div>

    {{-- Tabel resep --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Data Resep Pasien
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Informasi resep yang dikirim oleh dokter.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1100px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Kode Resep</th>
                        <th class="px-5 py-4">Nama Pasien</th>
                        <th class="px-5 py-4">Catatan Dokter</th>
                        <th class="px-5 py-4">Detail Obat</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($reseps as $index => $resep)
                        <tr class="transition hover:bg-slate-50">

                            <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                {{ $index + 1 }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="inline-flex rounded-md bg-slate-100 px-2.5 py-1 font-medium text-slate-700">
                                    {{ $resep->kode_resep }}
                                </span>
                            </td>

                            <td class="px-5 py-4">
                                <span class="font-semibold text-slate-800">
                                    {{ $resep->pendaftaran->pasien->nama ?? 'Pasien Tidak Ditemukan' }}
                                </span>
                            </td>

                            <td class="px-5 py-4 text-slate-600">
                                <div class="max-w-[220px] whitespace-normal">
                                    {{ $resep->catatan_dokter ?: '-' }}
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <div class="min-w-[230px] space-y-3">
                                    @forelse($resep->detailReseps as $detail)
                                        <div class="rounded-lg border border-slate-100 bg-slate-50 p-3">
                                            <p class="font-semibold text-slate-800">
                                                {{ $detail->obat->nama_obat ?? 'Obat tidak ditemukan' }}
                                            </p>

                                            <div class="mt-2 space-y-1 text-slate-600">
                                                <p>
                                                    <span class="text-slate-500">Jumlah:</span>
                                                    {{ $detail->jumlah }}
                                                    {{ $detail->obat->satuan ?? '' }}
                                                </p>

                                                <p class="whitespace-normal">
                                                    <span class="text-slate-500">Dosis:</span>
                                                    {{ $detail->dosis }}
                                                </p>

                                                @if($detail->instruksi_khusus)
                                                    <p class="whitespace-normal">
                                                        <span class="text-slate-500">Instruksi:</span>
                                                        {{ $detail->instruksi_khusus }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <span class="text-slate-400">
                                            Belum ada detail obat.
                                        </span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-5 py-4">
                                @if($resep->status === 'selesai')
                                    <span class="inline-flex rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Selesai
                                    </span>
                                @elseif($resep->status === 'diproses')
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                        Diproses
                                    </span>
                                @else
                                    <span class="inline-flex rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        Menunggu
                                    </span>
                                @endif
                            </td>

                            <td class="px-5 py-4">
                                <form
                                    action="{{ route('farmasi.updateStatus', $resep->id) }}"
                                    method="POST"
                                    class="min-w-[140px]"
                                >
                                    @csrf
                                    @method('PUT')

                                    <label for="status-{{ $resep->id }}"
                                        class="mb-1 block text-xs text-slate-500">
                                        Ubah status
                                    </label>

                                    <select
                                        id="status-{{ $resep->id }}"
                                        name="status"
                                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-700 outline-none transition focus:border-sky-500 focus:ring-2 focus:ring-sky-100"
                                        onchange="this.form.submit()"
                                    >
                                        <option value="menunggu"
                                            {{ $resep->status === 'menunggu' ? 'selected' : '' }}>
                                            Menunggu
                                        </option>
                                        <option value="diproses"
                                            {{ $resep->status === 'diproses' ? 'selected' : '' }}>
                                            Diproses
                                        </option>
                                        <option value="selesai"
                                            {{ $resep->status === 'selesai' ? 'selected' : '' }}>
                                            Selesai
                                        </option>
                                    </select>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <p class="font-semibold text-slate-700">
                                    Belum ada resep masuk
                                </p>
                                <p class="mt-1 text-sm text-slate-500">
                                    Resep yang dibuat dokter akan tampil di halaman ini.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-5 py-3">
            <p class="text-xs text-slate-500">
                Menampilkan {{ $reseps->count() }} resep.
                Geser tabel ke samping untuk melihat seluruh kolom.
            </p>
        </div>
    </div>

</div>
@endsection