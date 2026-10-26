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

        @if ($errors->any())
            <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="font-semibold">Periksa kembali data pendaftaran.</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pasien.pendaftaran.store') }}"
              method="POST"
              class="mt-6 space-y-6">
            @csrf

            <div>
                <label for="poli_id" class="mb-2 block text-sm font-medium text-slate-700">
                    Poli tujuan <span class="text-red-500">*</span>
                </label>

                <select id="poli_id" name="poli_id" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih poli</option>
                    @foreach ($polis as $poli)
                        <option value="{{ $poli->id }}"
                            @selected(old('poli_id') == $poli->id)>
                            {{ $poli->nama_poli }}
                        </option>
                    @endforeach
                </select>

                @if ($polis->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        Belum ada poli aktif. Silakan hubungi administrator rumah sakit.
                    </p>
                @endif
            </div>

            <div>
                <label for="jadwal_dokter_id" class="mb-2 block text-sm font-medium text-slate-700">
                    Jadwal dokter <span class="text-red-500">*</span>
                </label>

                <select id="jadwal_dokter_id" name="jadwal_dokter_id" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih jadwal dokter</option>
                    @foreach ($jadwals as $jadwal)
                        <option value="{{ $jadwal->id }}"
                            data-poli="{{ $jadwal->dokter->poli_id }}"
                            @selected(old('jadwal_dokter_id') == $jadwal->id)>
                            {{ $jadwal->dokter->user->name ?? 'Dokter' }}
                            · {{ $jadwal->dokter->poli->nama_poli ?? 'Poli' }}
                            · {{ $jadwal->hari }}
                            ({{ substr($jadwal->jam_mulai, 0, 5) }}–{{ substr($jadwal->jam_selesai, 0, 5) }})
                        </option>
                    @endforeach
                </select>

                <p id="jadwal-kosong-msg" class="mt-2 text-sm text-amber-700 hidden">
                    Belum ada dokter atau jadwal aktif pada poli ini. Silakan pilih poli lain atau hubungi informasi RS.
                </p>

                @if ($jadwals->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        Belum ada jadwal dokter aktif. Silakan hubungi administrator rumah sakit.
                    </p>
                @endif
            </div>

            <div>
                <label for="tanggal_kunjungan" class="mb-2 block text-sm font-medium text-slate-700">
                    Tanggal kunjungan <span class="text-red-500">*</span>
                </label>

                <input type="date" id="tanggal_kunjungan"
                       name="tanggal_kunjungan"
                       min="{{ now()->toDateString() }}"
                       value="{{ old('tanggal_kunjungan') }}"
                       required
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">
            </div>

            <div>
                <label for="jam_estimasi" class="mb-2 block text-sm font-medium text-slate-700">
                    Perkiraan jam kedatangan <span class="text-red-500">*</span>
                </label>

                <select id="jam_estimasi" name="jam_estimasi" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih rentang jam kedatangan</option>
                    <option value="08.00 - 09.00 WIB" @selected(old('jam_estimasi') === '08.00 - 09.00 WIB')>Pagi (08.00 - 09.00 WIB)</option>
                    <option value="09.00 - 10.00 WIB" @selected(old('jam_estimasi') === '09.00 - 10.00 WIB')>Pagi (09.00 - 10.00 WIB)</option>
                    <option value="10.00 - 11.00 WIB" @selected(old('jam_estimasi') === '10.00 - 11.00 WIB')>Siang (10.00 - 11.00 WIB)</option>
                    <option value="11.00 - 12.00 WIB" @selected(old('jam_estimasi') === '11.00 - 12.00 WIB')>Siang (11.00 - 12.00 WIB)</option>
                    <option value="13.00 - 14.00 WIB" @selected(old('jam_estimasi') === '13.00 - 14.00 WIB')>Siang (13.00 - 14.00 WIB)</option>
                    <option value="14.00 - 15.00 WIB" @selected(old('jam_estimasi') === '14.00 - 15.00 WIB')>Sore (14.00 - 15.00 WIB)</option>
                </select>
                <p class="mt-1 text-xs text-slate-500">
                    Rentang waktu ini membantu pengaturan antrean agar Anda tidak menunggu terlalu lama di ruang tunggu.
                </p>
            </div>

            <div>
                <label for="jenis_penjamin" class="mb-2 block text-sm font-medium text-slate-700">
                    Jenis penjamin <span class="text-red-500">*</span>
                </label>

                <select id="jenis_penjamin" name="jenis_penjamin" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">
                    <option value="">Pilih jenis penjamin</option>
                    <option value="Umum" @selected(old('jenis_penjamin') === 'Umum')>Umum</option>
                    <option value="BPJS" @selected(old('jenis_penjamin') === 'BPJS')>BPJS</option>
                    <option value="Asuransi" @selected(old('jenis_penjamin') === 'Asuransi')>Asuransi</option>
                </select>
            </div>

            <div id="nomor-peserta-wrapper">
                <label for="nomor_peserta" class="mb-2 block text-sm font-medium text-slate-700">
                    Nomor peserta
                </label>

                <input type="text" id="nomor_peserta" name="nomor_peserta"
                       value="{{ old('nomor_peserta') }}"
                       maxlength="50"
                       placeholder="Nomor kartu BPJS atau asuransi"
                       class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">

                <p class="mt-1 text-xs text-slate-500">
                    Wajib diisi untuk penjamin BPJS atau Asuransi.
                </p>
            </div>

            <div>
                <label for="keluhan_awal" class="mb-2 block text-sm font-medium text-slate-700">
                    Keluhan awal
                </label>

                <textarea id="keluhan_awal" name="keluhan_awal"
                          rows="4" maxlength="2000"
                          placeholder="Ceritakan keluhan yang dirasakan (opsional)"
                          class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm text-slate-700 focus:border-oasis-blue focus:outline-none focus:ring-2 focus:ring-blue-100">{{ old('keluhan_awal') }}</textarea>
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:justify-end">
                <a href="{{ route('pasien.pendaftaran.index') }}"
                   class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-50">
                    Batal
                </a>

                <button type="submit"
                        @disabled($polis->isEmpty() || $jadwals->isEmpty())
                        class="inline-flex items-center justify-center rounded-lg bg-oasis-blue px-5 py-2.5 text-sm font-semibold text-white hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-50">
                    Daftar Kunjungan
                </button>
            </div>
        </form>

        <div class="mt-8 border-t border-slate-100 pt-6">
            <h2 class="text-base font-semibold text-slate-800">
                Tahapan pendaftaran
            </h2>

            <ol class="mt-4 space-y-4">
                <li class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">1</span>
                    <div>
                        <p class="font-medium text-slate-700">Pilih poli dan jadwal dokter</p>
                        <p class="mt-1 text-sm text-slate-500">Tentukan tujuan kunjungan dan jadwal praktik yang tersedia.</p>
                    </div>
                </li>

                <li class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">2</span>
                    <div>
                        <p class="font-medium text-slate-700">Lengkapi informasi kunjungan</p>
                        <p class="mt-1 text-sm text-slate-500">Isi jenis penjamin, nomor peserta jika diperlukan, dan keluhan awal.</p>
                    </div>
                </li>

                <li class="flex gap-3">
                    <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-sm font-semibold text-slate-600">3</span>
                    <div>
                        <p class="font-medium text-slate-700">Dapatkan nomor antrean</p>
                        <p class="mt-1 text-sm text-slate-500">Nomor antrean diberikan setelah pendaftaran berhasil disimpan.</p>
                    </div>
                </li>
            </ol>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const poliSelect = document.getElementById('poli_id');
    const jadwalSelect = document.getElementById('jadwal_dokter_id');
    const penjaminSelect = document.getElementById('jenis_penjamin');
    const nomorPeserta = document.getElementById('nomor_peserta');
    const nomorPesertaWrapper = document.getElementById('nomor-peserta-wrapper');

    function filterJadwal() {
        const poliId = poliSelect.value;
        const jadwalKosongMsg = document.getElementById('jadwal-kosong-msg');
        let visibleCount = 0;

        Array.from(jadwalSelect.options).forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const match = poliId !== '' && option.dataset.poli === poliId;
            option.hidden = poliId !== '' && !match;

            if (match) {
                visibleCount++;
            }

            if (option.hidden && option.selected) {
                jadwalSelect.value = '';
            }
        });

        if (poliId !== '' && visibleCount === 0) {
            jadwalKosongMsg.classList.remove('hidden');
        } else {
            jadwalKosongMsg.classList.add('hidden');
        }
    }

    function updateNomorPeserta() {
        const wajib = penjaminSelect.value === 'BPJS'
            || penjaminSelect.value === 'Asuransi';

        nomorPeserta.required = wajib;
        nomorPesertaWrapper.classList.toggle('hidden', penjaminSelect.value === 'Umum');
    }

    poliSelect.addEventListener('change', filterJadwal);
    penjaminSelect.addEventListener('change', updateNomorPeserta);

    filterJadwal();
    updateNomorPeserta();
});
</script>
@endsection