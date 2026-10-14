
@extends('layouts.app')

@section('content')
<div class="mx-auto w-full max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('tagihan.index') }}"
           class="text-sm text-[#173D59] hover:underline">
            &larr; Kembali ke daftar tagihan
        </a>

        <h1 class="mt-3 text-2xl font-bold text-[#173D59]">
            Tambah Tagihan
        </h1>

        <p class="mt-1 text-sm text-gray-600">
            Isi data pendaftaran dan total biaya pasien.
        </p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
        @if ($errors->any())
            <div class="mb-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <p class="mb-2 font-semibold">
                    Periksa kembali data berikut:
                </p>
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tagihan.store') }}" method="POST">
            @csrf

            <div class="mb-5">
                <label for="nomor_tagihan"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Nomor Tagihan
                </label>

                <input
                    type="text"
                    id="nomor_tagihan"
                    name="nomor_tagihan"
                    value="{{ old('nomor_tagihan') }}"
                    maxlength="30"
                    required
                    placeholder="Contoh: TAG-20261014-001"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#4682A9] focus:outline-none focus:ring-2 focus:ring-[#91C8E4]"
                >
            </div>

            <div class="mb-5">
                <label for="pendaftaran_id"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Pendaftaran Pasien
                </label>

                <select
                    id="pendaftaran_id"
                    name="pendaftaran_id"
                    required
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm focus:border-[#4682A9] focus:outline-none focus:ring-2 focus:ring-[#91C8E4]"
                >
                    <option value="">Pilih pendaftaran</option>

                    @foreach ($pendaftarans as $pendaftaran)
                        <option
                            value="{{ $pendaftaran->id }}"
                            @selected(old('pendaftaran_id') == $pendaftaran->id)
                        >
                            {{ $pendaftaran->kode_pendaftaran }}
                            - {{ $pendaftaran->pasien?->nama ?? 'Pasien #' . $pendaftaran->pasien_id }}
                            - {{ $pendaftaran->tanggal_kunjungan?->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>

                @if ($pendaftarans->isEmpty())
                    <p class="mt-2 text-sm text-amber-700">
                        Tidak ada pendaftaran yang tersedia untuk dibuatkan tagihan.
                    </p>
                @endif
            </div>

            <div class="mb-6">
                <label for="total_tagihan"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Total Tagihan (Rp)
                </label>

                <input
                    type="number"
                    id="total_tagihan"
                    name="total_tagihan"
                    value="{{ old('total_tagihan') }}"
                    min="0.01"
                    max="9999999999.99"
                    step="0.01"
                    required
                    placeholder="Contoh: 250000"
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-[#4682A9] focus:outline-none focus:ring-2 focus:ring-[#91C8E4]"
                >
            </div>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <a href="{{ route('tagihan.index') }}"
                   class="rounded-lg border border-gray-300 px-5 py-3 text-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Batal
                </a>

                <button
                    type="submit"
                    @disabled($pendaftarans->isEmpty())
                    class="rounded-lg bg-[#173D59] px-5 py-3 text-sm font-medium text-white hover:bg-[#245779] disabled:cursor-not-allowed disabled:opacity-50"
                >
                    Simpan Tagihan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
