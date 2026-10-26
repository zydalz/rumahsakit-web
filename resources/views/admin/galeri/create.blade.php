@extends('layouts.app')

@section('title', 'Unggah Gambar Aset Web - PT Oasis Husada')
@section('header', 'Unggah Gambar Aset Web')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Unggah Gambar Aset Baru</h1>
            <p class="mt-1 text-sm text-slate-500">Unggah foto banner beranda, fasilitas rumah sakit, atau kegiatan medis.</p>
        </div>
        <a href="{{ route('admin.galeri.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50">
            &larr; Kembali
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
            <p class="font-semibold">Terjadi kesalahan pada data input:</p>
            <ul class="mt-2 list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <form action="{{ route('admin.galeri.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Judul --}}
            <div>
                <label for="judul" class="mb-2 block text-sm font-semibold text-slate-700">
                    Judul Aset / Gambar <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="judul" name="judul" value="{{ old('judul') }}" required maxlength="150"
                       placeholder="Contoh: Gedung Rawat Jalan Utama, Ruang Perawatan VIP"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
            </div>

            {{-- Kategori --}}
            <div>
                <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">
                    Kategori Penempatan <span class="text-rose-500">*</span>
                </label>
                <select id="kategori" name="kategori" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <option value="">-- Pilih Kategori --</option>
                    <option value="banner" @selected(old('kategori') === 'banner')>Banner Utama Halaman Depan</option>
                    <option value="fasilitas" @selected(old('kategori', 'fasilitas') === 'fasilitas')>Fasilitas Medis Rumah Sakit</option>
                    <option value="kegiatan" @selected(old('kategori') === 'kegiatan')>Galeri Kegiatan & Pelayanan</option>
                    <option value="layanan" @selected(old('kategori') === 'layanan')>Kartu Layanan Publik</option>
                </select>
                <p class="mt-1 text-xs text-slate-500">Kategori menentukan di mana gambar ini akan dimunculkan pada website publik.</p>
            </div>

            {{-- File Upload --}}
            <div>
                <label for="gambar" class="mb-2 block text-sm font-semibold text-slate-700">
                    File Gambar <span class="text-rose-500">*</span>
                </label>
                <div class="mt-1 flex justify-center rounded-xl border-2 border-dashed border-slate-300 px-6 pt-5 pb-6 hover:border-sky-400 transition bg-slate-50">
                    <div class="space-y-2 text-center">
                        <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        <div class="flex text-sm text-slate-600 justify-center">
                            <label for="gambar" class="relative cursor-pointer rounded-md font-semibold text-sky-700 hover:text-sky-800">
                                <span>Pilih file dari komputer</span>
                                <input id="gambar" name="gambar" type="file" required accept="image/png,image/jpeg,image/webp" class="sr-only" onchange="previewImage(this)">
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">Format: PNG, JPG, JPEG, atau WEBP (Maksimal 5 MB)</p>
                    </div>
                </div>

                <div id="image-preview-container" class="mt-4 hidden">
                    <p class="text-xs font-semibold text-slate-600 mb-2">Pratinjau Gambar:</p>
                    <img id="image-preview" src="#" alt="Pratinjau" class="max-h-56 rounded-lg object-contain border border-slate-200 p-1">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">
                    Deskripsi / Keterangan Singkat
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3" maxlength="1000"
                          placeholder="Jelaskan fasilitas atau kegiatan yang ada di gambar (opsional)"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Urutan & Status --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="urutan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Urutan Tampilan
                    </label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', 0) }}" min="0"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <p class="mt-1 text-xs text-slate-400">Angka lebih kecil tampil lebih awal (0, 1, 2, ...)</p>
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2.5 text-sm font-medium text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                               class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                        Aktifkan dan tampilkan di website
                    </label>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div class="flex items-center justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('admin.galeri.index') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit" class="rounded-lg bg-sky-700 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-sky-800">
                    Simpan Gambar Aset
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input) {
    const previewContainer = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
