@extends('layouts.app')

@section('title', 'Edit Gambar Aset Web - PT Oasis Husada')
@section('header', 'Edit Gambar Aset Web')

@section('content')
<div class="mx-auto max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Gambar Aset</h1>
            <p class="mt-1 text-sm text-slate-500">Perbarui judul, kategori, atau ganti file gambar aset.</p>
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
        <form action="{{ route('admin.galeri.update', $galeri->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label for="judul" class="mb-2 block text-sm font-semibold text-slate-700">
                    Judul Aset / Gambar <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="judul" name="judul" value="{{ old('judul', $galeri->judul) }}" required maxlength="150"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
            </div>

            {{-- Kategori --}}
            <div>
                <label for="kategori" class="mb-2 block text-sm font-semibold text-slate-700">
                    Kategori Penempatan <span class="text-rose-500">*</span>
                </label>
                <select id="kategori" name="kategori" required
                        class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                    <option value="banner" @selected(old('kategori', $galeri->kategori) === 'banner')>Banner Utama Halaman Depan</option>
                    <option value="fasilitas" @selected(old('kategori', $galeri->kategori) === 'fasilitas')>Fasilitas Medis Rumah Sakit</option>
                    <option value="kegiatan" @selected(old('kategori', $galeri->kategori) === 'kegiatan')>Galeri Kegiatan & Pelayanan</option>
                    <option value="layanan" @selected(old('kategori', $galeri->kategori) === 'layanan')>Kartu Layanan Publik</option>
                </select>
            </div>

            {{-- Foto Sekarang & Upload Baru --}}
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">
                    Gambar Saat Ini
                </label>
                <div class="mb-4 flex items-center gap-4">
                    <img src="{{ $galeri->gambar_url }}" alt="{{ $galeri->judul }}" class="h-28 w-44 rounded-xl object-cover border border-slate-200 p-1 shadow-sm">
                    <p class="text-xs text-slate-500">Unggah file baru di bawah ini hanya jika ingin mengganti gambar di samping.</p>
                </div>

                <label for="gambar" class="mb-2 block text-sm font-semibold text-slate-700">
                    Ganti File Gambar (Opsional)
                </label>
                <input id="gambar" name="gambar" type="file" accept="image/png,image/jpeg,image/webp"
                       class="w-full rounded-lg border border-slate-300 px-4 py-2 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100"
                       onchange="previewImage(this)">
                <p class="mt-1 text-xs text-slate-400">Format: PNG, JPG, JPEG, atau WEBP (Maksimal 5 MB)</p>

                <div id="image-preview-container" class="mt-4 hidden">
                    <p class="text-xs font-semibold text-slate-600 mb-2">Pratinjau Pengganti:</p>
                    <img id="image-preview" src="#" alt="Pratinjau Pengganti" class="max-h-56 rounded-lg object-contain border border-slate-200 p-1">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="mb-2 block text-sm font-semibold text-slate-700">
                    Deskripsi / Keterangan Singkat
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3" maxlength="1000"
                          class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">{{ old('deskripsi', $galeri->deskripsi) }}</textarea>
            </div>

            {{-- Urutan & Status --}}
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="urutan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Urutan Tampilan
                    </label>
                    <input type="number" id="urutan" name="urutan" value="{{ old('urutan', $galeri->urutan) }}" min="0"
                           class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-100">
                </div>

                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2.5 text-sm font-medium text-slate-700 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $galeri->is_active ? '1' : '0') == '1' ? 'checked' : '' }}
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
                    Simpan Perubahan
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
