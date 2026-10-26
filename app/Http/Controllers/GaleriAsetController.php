<?php

namespace App\Http\Controllers;

use App\Models\GaleriAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GaleriAsetController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');

        $query = GaleriAset::query();

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $asets = $query->orderBy('kategori')
            ->orderBy('urutan')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.galeri.index', compact('asets', 'kategori'));
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'in:banner,fasilitas,kegiatan,layanan'],
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $file = $request->file('gambar');
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $destinationPath = public_path('uploads/galeri');
        if (! File::isDirectory($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true, true);
        }

        $file->move($destinationPath, $fileName);

        GaleriAset::create([
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'file_path' => $fileName,
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urutan' => $validated['urutan'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Gambar aset web berhasil diunggah.');
    }

    public function edit(GaleriAset $galeri)
    {
        return view('admin.galeri.edit', compact('galeri'));
    }

    public function update(Request $request, GaleriAset $galeri)
    {
        $validated = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'kategori' => ['required', 'string', 'in:banner,fasilitas,kegiatan,layanan'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'urutan' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'judul' => $validated['judul'],
            'kategori' => $validated['kategori'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'urutan' => $validated['urutan'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('uploads/galeri');

            if (! File::isDirectory($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true, true);
            }

            // Hapus file lama jika ada
            $oldPath = public_path('uploads/galeri/' . $galeri->file_path);
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $file->move($destinationPath, $fileName);
            $data['file_path'] = $fileName;
        }

        $galeri->update($data);

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Data aset web berhasil diperbarui.');
    }

    public function destroy(GaleriAset $galeri)
    {
        $filePath = public_path('uploads/galeri/' . $galeri->file_path);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        $galeri->delete();

        return redirect()->route('admin.galeri.index')
            ->with('success', 'Aset gambar berhasil dihapus.');
    }
}
