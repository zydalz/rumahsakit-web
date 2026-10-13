<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreObatRequest;
use App\Http\Requests\UpdateObatRequest;
use App\Models\Obat;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $obats = Obat::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_obat', 'like', "%{$search}%")
                        ->orWhere('kode_obat', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('obat.index', compact('obats', 'search'));
    }

    public function create()
    {
        return view('obat.create');
    }

    public function store(StoreObatRequest $request)
    {
        Obat::create($request->validated());

        return redirect()
            ->route('farmasi.obat.index')
            ->with('success', 'Data obat berhasil ditambahkan.');
    }

    public function show(Obat $obat)
    {
        $obat->load('riwayatStok.user');

        return view('obat.show', compact('obat'));
    }

    public function edit(Obat $obat)
    {
        return view('obat.edit', compact('obat'));
    }

    public function update(UpdateObatRequest $request, Obat $obat)
    {
        $obat->update($request->validated());

        return redirect()
            ->route('farmasi.obat.index')
            ->with('success', 'Data obat berhasil diperbarui.');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();

        return redirect()
            ->route('farmasi.obat.index')
            ->with('success', 'Data obat berhasil dihapus.');
    }
}