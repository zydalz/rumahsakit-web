<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Http\Requests\StorePoliRequest;
use App\Http\Requests\UpdatePoliRequest;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::withCount(['dokters', 'pendaftarans'])->latest()->get();
        return view('admin.poli.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.poli.create');
    }

    public function store(StorePoliRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Poli::create($data);
        return redirect()->route('admin.poli.index')->with('success', 'Data poliklinik berhasil ditambahkan.');
    }

    public function edit(Poli $poli)
    {
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(UpdatePoliRequest $request, Poli $poli)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $poli->update($data);
        return redirect()->route('admin.poli.index')->with('success', 'Data poliklinik berhasil diperbarui.');
    }

    public function destroy(Poli $poli)
    {
        if ($poli->pendaftarans()->exists()) {
            return redirect()->route('admin.poli.index')->with('error', 'Poliklinik tidak dapat dihapus karena sudah memiliki data riwayat pendaftaran pasien.');
        }

        $poli->delete();
        return redirect()->route('admin.poli.index')->with('success', 'Data poliklinik berhasil dihapus.');
    }
}
