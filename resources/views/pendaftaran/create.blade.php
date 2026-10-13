@extends('layouts.public')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <a href="{{ route('pasien.pendaftaran.index') }}"
       class="text-sm font-medium text-oasis-blue hover:underline">
        &larr; Kembali ke Riwayat
    </a>

    <div class="mt-5 rounded-xl border border-slate-200 bg-white p-6 sm:p-8">
        <h1 class="text-2xl font-bold text-slate-800">
            Pendaftaran Rawat Jalan
        </h1>

        <p class="mt-2 text-sm leading-6 text-slate-500">
            Pilih poli dan jadwal dokter untuk melakukan pendaftaran kunjungan.
        </p>

        <div class="mt-6 rounded-lg border border-blue-100 bg-blue-50 p-4">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 text-blue-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="22" height="22" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 16v-4"/>
                        <path d="M12 8h.01"/>
                    </svg>
                </div>

                <div>
                    <h2 class="font-semibold text-blue-900">
                        Formulir belum tersedia
                    </h2>
                    <p class="mt-1 text-sm leading-6 text-blue-800">
                        Pemilihan poli dan jadwal dokter akan tersedia
                        setelah integrasi data poli, dokter, dan jadwal praktik
                        selesai.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-base font-semibold text-slate-800">
                Tahapan pendaftaran
            </h2>

            <ol class="mt-4 space-y-4">
                <li class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center
                                 justify-center rounded-full bg-slate-100
                                 text-sm font-semibold text-slate-600">
                        1
                    </span>
                    <div>
                        <p class="font-medium text-slate-700">
                            Pilih poli dan jadwal dokter
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Tentukan tujuan kunjungan dan tanggal praktik
                            yang tersedia.
                        </p>
                    </div>
                </li>

                <li class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center
                                 justify-center rounded-full bg-slate-100
                                 text-sm font-semibold text-slate-600">
                        2
                    </span>
                    <div>
                        <p class="font-medium text-slate-700">
                            Lengkapi informasi kunjungan
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Isi jenis penjamin dan keluhan awal jika ada.
                        </p>
                    </div>
                </li>

                <li class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center
                                 justify-center rounded-full bg-slate-100
                                 text-sm font-semibold text-slate-600">
                        3
                    </span>
                    <div>
                        <p class="font-medium text-slate-700">
                            Dapatkan nomor antrean
                        </p>
                        <p class="mt-1 text-sm text-slate-500">
                            Nomor antrean diberikan setelah pendaftaran
                            berhasil disimpan.
                        </p>
                    </div>
                </li>
            </ol>
        </div>
    </div>
</div>
@endsection