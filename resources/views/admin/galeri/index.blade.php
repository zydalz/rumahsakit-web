@extends('layouts.app')

@section('title', 'Kelola Gambar & Galeri Web - PT Oasis Husada')
@section('header', 'Kelola Gambar & Galeri Web')

@section('content')
<div class="space-y-6">

    {{-- Header & Tombol Tambah --}}
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Kelola Aset Visual & Galeri
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Atur gambar banner halaman utama, foto fasilitas medis, dan galeri kegiatan rumah sakit secara dinamis.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank"
               class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                <svg class="h-4 w-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Lihat Beranda Publik
            </a>

            <a href="{{ route('admin.galeri.create') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-sky-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Unggah Gambar Baru
            </a>
        </div>
    </div>

    {{-- Notifikasi Sukses / Error --}}
    @if(session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800 shadow-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    {{-- Tab Filter Kategori --}}
    <div class="flex flex-wrap items-center gap-2 border-b border-slate-200 pb-3 text-sm">
        <a href="{{ route('admin.galeri.index') }}"
           class="rounded-lg px-3.5 py-2 font-semibold transition {{ empty($kategori) ? 'bg-sky-700 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            Semua Aset
        </a>
        <a href="{{ route('admin.galeri.index', ['kategori' => 'banner']) }}"
           class="rounded-lg px-3.5 py-2 font-semibold transition {{ $kategori === 'banner' ? 'bg-sky-700 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            Banner Utama Beranda
        </a>
        <a href="{{ route('admin.galeri.index', ['kategori' => 'fasilitas']) }}"
           class="rounded-lg px-3.5 py-2 font-semibold transition {{ $kategori === 'fasilitas' ? 'bg-sky-700 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            Fasilitas Rumah Sakit
        </a>
        <a href="{{ route('admin.galeri.index', ['kategori' => 'kegiatan']) }}"
           class="rounded-lg px-3.5 py-2 font-semibold transition {{ $kategori === 'kegiatan' ? 'bg-sky-700 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            Galeri Kegiatan Medis
        </a>
        <a href="{{ route('admin.galeri.index', ['kategori' => 'layanan']) }}"
           class="rounded-lg px-3.5 py-2 font-semibold transition {{ $kategori === 'layanan' ? 'bg-sky-700 text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100' }}">
            Kartu Layanan Publik
        </a>
    </div>

    {{-- Grid Kartu Galeri --}}
    @if($asets->isEmpty())
        <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center shadow-sm">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <h3 class="mt-4 font-bold text-slate-700">Belum Ada Gambar Aset</h3>
            <p class="mt-1 text-sm text-slate-500">Unggah foto banner atau fasilitas rumah sakit untuk ditampilkan di web publik.</p>
            <div class="mt-5">
                <a href="{{ route('admin.galeri.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-sky-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-sky-800">
                    + Unggah Sekarang
                </a>
            </div>
        </div>
    @else
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($asets as $aset)
                <div class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                    {{-- Gambar --}}
                    <div class="relative h-48 w-full bg-slate-100 overflow-hidden">
                        <img src="{{ $aset->gambar_url }}" alt="{{ $aset->judul }}" class="h-full w-full object-cover transition duration-300 hover:scale-105">
                        
                        {{-- Kategori Badge --}}
                        <span class="absolute top-3 left-3 rounded-full px-2.5 py-0.5 text-xs font-bold uppercase tracking-wide backdrop-blur-md
                            {{ $aset->kategori === 'banner' ? 'bg-indigo-600/90 text-white' : ($aset->kategori === 'fasilitas' ? 'bg-sky-600/90 text-white' : 'bg-emerald-600/90 text-white') }}">
                            {{ $aset->kategori }}
                        </span>

                        {{-- Status Pill --}}
                        <span class="absolute top-3 right-3 rounded-full px-2.5 py-0.5 text-xs font-semibold
                            {{ $aset->is_active ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : 'bg-rose-100 text-rose-800 border border-rose-300' }}">
                            {{ $aset->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>

                    {{-- Konten --}}
                    <div class="flex flex-1 flex-col justify-between p-5">
                        <div>
                            <h3 class="font-bold text-slate-800 text-base leading-snug">
                                {{ $aset->judul }}
                            </h3>
                            <p class="mt-2 text-xs text-slate-500 line-clamp-2">
                                {{ $aset->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}
                            </p>
                        </div>

                        {{-- Footer Aksi --}}
                        <div class="mt-5 flex items-center justify-between border-t border-slate-100 pt-3 text-xs">
                            <span class="text-slate-400">Urutan: <strong>{{ $aset->urutan }}</strong></span>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.galeri.edit', $aset->id) }}"
                                   class="font-semibold text-sky-700 hover:text-sky-900 px-2 py-1 rounded hover:bg-sky-50 transition">
                                    Edit
                                </a>

                                <form action="{{ route('admin.galeri.destroy', $aset->id) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset gambar ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-semibold text-rose-600 hover:text-rose-800 px-2 py-1 rounded hover:bg-rose-50 transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $asets->links() }}
        </div>
    @endif

</div>
@endsection
