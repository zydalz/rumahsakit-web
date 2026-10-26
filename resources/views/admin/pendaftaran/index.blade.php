@extends('layouts.app')

@section('title', 'Verifikasi Pendaftaran Rawat Jalan - PT Oasis Husada')
@section('header', 'Verifikasi & Konfirmasi Kunjungan')

@section('content')
<div class="space-y-6 p-6 lg:p-8">

    {{-- Pengantar --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Pendaftaran Rawat Jalan Pasien
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Verifikasi ketersediaan dokter dan konfirmasi jadwal pasien agar pasien tidak menunggu tanpa kepastian.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tab Filter Status --}}
    <div class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        <a href="{{ route('admin.pendaftaran.index') }}"
           class="rounded-lg px-3.5 py-2 text-xs font-semibold transition
           {{ empty($status) ? 'bg-sky-700 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Semua ({{ $counts['semua'] }})
        </a>

        <a href="{{ route('admin.pendaftaran.index', ['status' => 'menunggu_konfirmasi']) }}"
           class="rounded-lg px-3.5 py-2 text-xs font-semibold transition
           {{ $status === 'menunggu_konfirmasi' ? 'bg-amber-600 text-white' : 'bg-white border border-slate-200 text-amber-700 hover:bg-amber-50' }}">
            Perlu Konfirmasi ({{ $counts['menunggu'] }})
        </a>

        <a href="{{ route('admin.pendaftaran.index', ['status' => 'dikonfirmasi']) }}"
           class="rounded-lg px-3.5 py-2 text-xs font-semibold transition
           {{ $status === 'dikonfirmasi' ? 'bg-emerald-600 text-white' : 'bg-white border border-slate-200 text-emerald-700 hover:bg-emerald-50' }}">
            Dikonfirmasi ({{ $counts['dikonfirmasi'] }})
        </a>

        <a href="{{ route('admin.pendaftaran.index', ['status' => 'selesai']) }}"
           class="rounded-lg px-3.5 py-2 text-xs font-semibold transition
           {{ $status === 'selesai' ? 'bg-slate-700 text-white' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
            Selesai ({{ $counts['selesai'] }})
        </a>

        <a href="{{ route('admin.pendaftaran.index', ['status' => 'dibatalkan']) }}"
           class="rounded-lg px-3.5 py-2 text-xs font-semibold transition
           {{ $status === 'dibatalkan' ? 'bg-rose-600 text-white' : 'bg-white border border-slate-200 text-rose-700 hover:bg-rose-50' }}">
            Dibatalkan ({{ $counts['dibatalkan'] }})
        </a>
    </div>

    {{-- Tabel Pendaftaran --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-5 py-4">Kode / Antrean</th>
                        <th class="px-5 py-4">Data Pasien</th>
                        <th class="px-5 py-4">Poli & Dokter</th>
                        <th class="px-5 py-4">Tgl & Jam Estimasi</th>
                        <th class="px-5 py-4">Status</th>
                        <th class="px-5 py-4 text-center">Aksi Admin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($pendaftarans as $item)
                        <tr class="hover:bg-slate-50/75 transition">
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800">{{ $item->kode_pendaftaran }}</span>
                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-md bg-sky-100 px-2 py-0.5 text-xs font-bold text-sky-800">
                                        Antrean #{{ $item->nomor_antrean }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $item->pasien?->nama ?? 'Pasien' }}</p>
                                <p class="text-xs text-slate-400">No. RM: {{ $item->pasien?->nomor_rm ?? '-' }}</p>
                                <p class="text-xs text-slate-500">Penjamin: {{ $item->jenis_penjamin }}</p>
                            </td>

                            <td class="px-5 py-4">
                                <p class="font-semibold text-slate-800">{{ $item->poli?->nama_poli ?? '-' }}</p>
                                <p class="text-xs text-slate-500">{{ $item->jadwalDokter?->dokter?->user?->name ?? 'Dokter Praktik' }}</p>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="font-medium text-slate-700">
                                    {{ $item->tanggal_kunjungan ? $item->tanggal_kunjungan->format('d/m/Y') : '-' }}
                                </p>
                                <p class="text-xs text-sky-700 font-semibold mt-0.5">
                                    {{ $item->jam_estimasi ?? 'Belum ditentukan' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 whitespace-nowrap">
                                @if(in_array($item->status, ['menunggu', 'menunggu_konfirmasi']))
                                    <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-800">
                                        Menunggu Konfirmasi
                                    </span>
                                @elseif($item->status === 'dikonfirmasi')
                                    <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800">
                                        Dikonfirmasi
                                    </span>
                                @elseif($item->status === 'sedang_diperiksa')
                                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800">
                                        Sedang Diperiksa
                                    </span>
                                @elseif($item->status === 'selesai')
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-700">
                                        Selesai
                                    </span>
                                @elseif($item->status === 'dibatalkan')
                                    <span class="inline-flex rounded-full bg-rose-100 px-3 py-1 text-xs font-bold text-rose-800">
                                        Dibatalkan
                                    </span>
                                @endif

                                @if($item->catatan_admin)
                                    <p class="mt-1 text-xs text-slate-400 italic max-w-xs truncate" title="{{ $item->catatan_admin }}">
                                        "{{ $item->catatan_admin }}"
                                    </p>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-center">
                                @if(in_array($item->status, ['menunggu', 'menunggu_konfirmasi']))
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Tombol Konfirmasi --}}
                                        <form method="POST" action="{{ route('admin.pendaftaran.konfirmasi', $item->id) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    onclick="return confirm('Konfirmasi kunjungan pasien ini? Pasien akan dipersilakan hadir sesuai jam.')"
                                                    class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-emerald-700">
                                                Konfirmasi
                                            </button>
                                        </form>

                                        {{-- Tombol Tolak/Batal --}}
                                        <button type="button"
                                                onclick="bukaModalBatal('{{ $item->id }}', '{{ $item->kode_pendaftaran }}', '{{ $item->pasien?->nama }}')"
                                                class="rounded-lg border border-rose-300 bg-white px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-50">
                                            Tolak
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Selesai Diverifikasi</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-sm text-slate-400">
                                Tidak ada data pendaftaran pada status ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pendaftarans->hasPages())
            <div class="border-t border-slate-100 p-4">
                {{ $pendaftarans->links() }}
            </div>
        @endif
    </div>

</div>

{{-- Modal Pembatalan Pendaftaran --}}
<div id="modal-batal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-slate-900/60 p-4 sm:p-6 flex items-center justify-center">
    <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-xl border border-slate-200">
        <h3 class="text-lg font-bold text-slate-800">
            Batalkan Pendaftaran Pasien
        </h3>
        <p id="batal-info" class="mt-1 text-xs text-slate-500">
            Berikan alasan pembatalan agar pasien mengetahui alasan dokter berhalangan atau kuota penuh.
        </p>

        <form id="form-batal" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="catatan_admin" class="block text-xs font-semibold text-slate-700">
                    Alasan Pembatalan <span class="text-red-500">*</span>
                </label>
                <textarea id="catatan_admin" name="catatan_admin" rows="3" required
                          placeholder="Contoh: Dokter berhalangan hadir / Kuota poli telah penuh."
                          class="mt-1 w-full rounded-lg border border-slate-300 p-2.5 text-sm focus:border-rose-500 focus:outline-none focus:ring-1 focus:ring-rose-500"></textarea>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="tutupModalBatal()"
                        class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    Kembali
                </button>
                <button type="submit"
                        class="rounded-lg bg-rose-600 px-4 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                    Ya, Batalkan Pendaftaran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function bukaModalBatal(id, kode, nama) {
    const modal = document.getElementById('modal-batal');
    const form = document.getElementById('form-batal');
    const info = document.getElementById('batal-info');

    form.action = `/admin/pendaftaran/${id}/batalkan`;
    info.textContent = `Membatalkan pendaftaran ${kode} atas nama pasien ${nama}.`;
    modal.classList.remove('hidden');
}

function tutupModalBatal() {
    document.getElementById('modal-batal').classList.add('hidden');
}
</script>
@endsection
