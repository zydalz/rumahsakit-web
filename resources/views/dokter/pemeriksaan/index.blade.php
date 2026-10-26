@extends('layouts.app')

@section('title', 'Antrean & Pemeriksaan Pasien - PT Oasis Husada')
@section('header', 'Antrean & Pemeriksaan Poli ' . ($dokter->poli->nama_poli ?? ''))

@section('content')
<div class="space-y-6">

    {{-- Header & Tindakan Cepat --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Pemeriksaan & Antrean Poli {{ $dokter->poli->nama_poli ?? 'Umum' }}
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Atur giliran masuk pasien, panggil antrean berikutnya, dan input rekam medis pasien.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            {{-- Tombol Buka Monitor Display Antrean Ruang Tunggu --}}
            <a href="{{ route('antrean.display', ['poli_id' => $dokter->poli_id]) }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Layar Display Ruang Tunggu
            </a>

            {{-- Tombol Panggil Antrean Selanjutnya --}}
            <form action="{{ route('dokter.antrean.panggilSelanjutnya') }}" method="POST">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path>
                    </svg>
                    Panggil Antrean Berikutnya
                </button>
            </form>
        </div>
    </div>

    {{-- Notifikasi Khusus --}}
    @if(session('warning'))
        <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Kartu Pasien yang Sedang Dipanggil / Diperiksa Saat Ini (Active Station) --}}
    <div class="overflow-hidden rounded-2xl border-2 {{ $antreanSaatIni ? 'border-sky-500 bg-gradient-to-r from-sky-50/80 via-white to-sky-50/50' : 'border-slate-200 bg-white' }} p-6 shadow-sm">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-3 w-3 rounded-full {{ $antreanSaatIni ? 'bg-sky-500 animate-ping' : 'bg-slate-300' }}"></span>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        {{ $antreanSaatIni ? 'Pasien Sedang Berada di Ruang Periksa / Dipanggil' : 'Ruang Periksa Tersedia' }}
                    </span>
                </div>

                @if($antreanSaatIni)
                    <div class="mt-3 flex items-baseline gap-3">
                        <span class="font-mono text-4xl font-extrabold text-sky-800">
                            #{{ str_pad($antreanSaatIni->nomor_antrean, 2, '0', STR_PAD_LEFT) }}
                        </span>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800">
                                {{ $antreanSaatIni->pasien->nama ?? 'Pasien' }}
                            </h3>
                            <p class="text-xs text-slate-500">
                                No. RM: <span class="font-mono font-medium text-slate-700">{{ $antreanSaatIni->pasien->nomor_rm ?? '-' }}</span>
                                &bull; Kedatangan: {{ $antreanSaatIni->jam_estimasi ?: '08:00 - 09:00' }}
                            </p>
                        </div>
                    </div>

                    @if($antreanSaatIni->keluhan_awal)
                        <p class="mt-2 text-xs text-slate-600 bg-white/80 p-2.5 rounded-lg border border-slate-200 inline-block">
                            <span class="font-semibold text-slate-700">Keluhan:</span> {{ $antreanSaatIni->keluhan_awal }}
                        </p>
                    @endif
                @else
                    <p class="mt-2 text-slate-600 text-sm">
                        Belum ada pasien yang sedang dipanggil ke dalam ruangan. Silakan tekan tombol <span class="font-semibold text-sky-700">"Panggil Antrean Berikutnya"</span> untuk memulai giliran pasien.
                    </p>
                @endif
            </div>

            @if($antreanSaatIni)
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- Tombol Mulai Periksa --}}
                    <form action="{{ route('dokter.antrean.mulaiPeriksa', $antreanSaatIni->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow transition hover:bg-emerald-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Mulai Periksa Pasien
                        </button>
                    </form>

                    {{-- Tombol Panggil Ulang Suara --}}
                    <form action="{{ route('dokter.antrean.panggil', $antreanSaatIni->id) }}" method="POST">
                        @csrf
                        <button type="submit"
                                title="Panggil ulang panggilan suara nomor antrean ini"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                            <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                            Panggil Ulang
                        </button>
                    </form>

                    {{-- Tombol Lewati / Skip --}}
                    <form action="{{ route('dokter.antrean.lewati', $antreanSaatIni->id) }}" method="POST"
                          onsubmit="return confirm('Apakah Anda yakin ingin melewati antrean #{{ $antreanSaatIni->nomor_antrean }} ({{ $antreanSaatIni->pasien->nama ?? '' }})? Pasien dapat dipanggil kembali nanti.');">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                            </svg>
                            Lewati (Skip)
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    {{-- Kartu Metrik Antrean Hari Ini --}}
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
            <p class="text-xs font-medium text-slate-500">Total Kunjungan Hari Ini</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">{{ $statsAntrean['total_hari_ini'] ?? 0 }}</p>
        </div>

        <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-amber-700">Menunggu Giliran</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">{{ $statsAntrean['menunggu'] ?? 0 }}</p>
        </div>

        <div class="rounded-xl border border-rose-200 bg-rose-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-rose-700">Dilewati (Belum Hadir)</p>
            <p class="mt-2 text-2xl font-bold text-rose-600">{{ $statsAntrean['dilewati'] ?? 0 }}</p>
        </div>

        <div class="rounded-xl border border-emerald-200 bg-emerald-50/60 p-4 shadow-sm">
            <p class="text-xs font-medium text-emerald-700">Selesai Diperiksa</p>
            <p class="mt-2 text-2xl font-bold text-emerald-700">{{ $statsAntrean['selesai'] ?? 0 }}</p>
        </div>
    </div>

    {{-- Tabel Seluruh Antrean Poli --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-5 py-4">
            <h3 class="font-semibold text-slate-800">
                Daftar Seluruh Antrean & Pasien Poli
            </h3>
            <p class="mt-1 text-sm text-slate-500">
                Informasi nomor antrean, jadwal kedatangan, dan status pelayanan pasien.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[950px] text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                    <tr>
                        <th class="px-5 py-4">No. Antrean</th>
                        <th class="px-5 py-4">Kode & Kedatangan</th>
                        <th class="px-5 py-4">Pasien & No. RM</th>
                        <th class="px-5 py-4">Keluhan Awal</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi Pelayanan</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($pendaftarans as $pendaftaran)
                        <tr class="transition hover:bg-slate-50/80 {{ in_array($pendaftaran->status, ['dipanggil', 'sedang_diperiksa']) ? 'bg-sky-50/40' : '' }}">
                            {{-- Nomor Antrean --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-mono text-lg font-extrabold {{ in_array($pendaftaran->status, ['dipanggil', 'sedang_diperiksa']) ? 'text-sky-700' : 'text-slate-800' }}">
                                    #{{ str_pad($pendaftaran->nomor_antrean, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            {{-- Kode & Kedatangan --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                <span class="font-mono text-xs font-semibold text-slate-700">
                                    {{ $pendaftaran->kode_pendaftaran }}
                                </span>
                                <p class="mt-0.5 text-xs text-slate-400">
                                    {{ $pendaftaran->tanggal_kunjungan ? $pendaftaran->tanggal_kunjungan->format('d/m/Y') : '-' }}
                                </p>
                                <span class="inline-flex mt-1 rounded bg-slate-100 px-2 py-0.5 text-[11px] font-medium text-slate-600">
                                    {{ $pendaftaran->jam_estimasi ?: '08:00 - 09:00' }}
                                </span>
                            </td>

                            {{-- Pasien --}}
                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">
                                    {{ $pendaftaran->pasien->nama ?? $pendaftaran->pasien->user->name ?? 'Pasien' }}
                                </p>
                                <p class="mt-0.5 text-xs text-slate-500">
                                    No. RM: <span class="font-mono text-slate-700">{{ $pendaftaran->pasien->nomor_rm ?? '-' }}</span>
                                    &bull; {{ $pendaftaran->jenis_penjamin }}
                                </p>
                            </td>

                            {{-- Keluhan Awal --}}
                            <td class="px-5 py-4 text-slate-600">
                                <div class="max-w-[220px] whitespace-normal text-xs">
                                    {{ $pendaftaran->keluhan_awal ?: 'Tidak ada catatan keluhan.' }}
                                </div>
                            </td>

                            {{-- Status Badge --}}
                            <td class="whitespace-nowrap px-5 py-4">
                                @if($pendaftaran->status === 'selesai')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">
                                        <svg class="h-3 w-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Selesai
                                    </span>
                                @elseif($pendaftaran->status === 'sedang_diperiksa')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2.5 py-1 text-xs font-semibold text-indigo-800">
                                        <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                                        Sedang Diperiksa
                                    </span>
                                @elseif($pendaftaran->status === 'dipanggil')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-800">
                                        <span class="h-2 w-2 rounded-full bg-sky-500 animate-ping"></span>
                                        Dipanggil
                                    </span>
                                @elseif($pendaftaran->status === 'dilewati')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800">
                                        Dilewati (Skip)
                                    </span>
                                @elseif($pendaftaran->status === 'dibatalkan')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800">
                                        Menunggu Giliran
                                    </span>
                                @endif
                            </td>

                            {{-- Aksi Pelayanan --}}
                            <td class="whitespace-nowrap px-5 py-4 text-center">
                                @if($pendaftaran->pemeriksaan)
                                    <a href="{{ route('dokter.pemeriksaan.show', $pendaftaran->pemeriksaan->id) }}"
                                       class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                        Lihat Rekam Medis
                                    </a>
                                @elseif(in_array($pendaftaran->status, ['dipanggil', 'sedang_diperiksa']))
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('dokter.antrean.mulaiPeriksa', $pendaftaran->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                                                Periksa Pasien
                                            </button>
                                        </form>

                                        <form action="{{ route('dokter.antrean.lewati', $pendaftaran->id) }}" method="POST"
                                              onsubmit="return confirm('Lewati antrean ini?');">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-700 transition hover:bg-rose-100">
                                                Lewati
                                            </button>
                                        </form>
                                    </div>
                                @elseif($pendaftaran->status !== 'dibatalkan')
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('dokter.antrean.panggil', $pendaftaran->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-sky-700 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-sky-800">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"></path></svg>
                                                Panggil
                                            </button>
                                        </form>

                                        <form action="{{ route('dokter.pemeriksaan.create', $pendaftaran->id) }}" method="GET">
                                            <button type="submit"
                                                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                                                Langsung Periksa
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Kunjungan Batal</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <p class="font-semibold text-slate-700">Belum ada kunjungan pasien di poli ini</p>
                                <p class="mt-1 text-sm text-slate-500">Pasien yang dikonfirmasi oleh admin akan muncul pada daftar antrean ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-slate-100 bg-slate-50 px-5 py-3">
            <p class="text-xs text-slate-500">Menampilkan total {{ $pendaftarans->count() }} data kunjungan pasien.</p>
        </div>
    </div>

</div>

{{-- Audio Panggilan Suara Menggunakan Browser SpeechSynthesis --}}
@if(session('audio_panggil'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const panggilData = @json(session('audio_panggil'));
        if ('speechSynthesis' in window && panggilData) {
            const pesanSuara = `Nomor antrean ${panggilData.nomor}. Atas nama ${panggilData.nama}. Silakan menuju ${panggilData.poli}.`;
            const utterance = new SpeechSynthesisUtterance(pesanSuara);
            utterance.lang = 'id-ID';
            utterance.rate = 0.9;
            window.speechSynthesis.speak(utterance);
        }
    });
</script>
@endif
@endsection