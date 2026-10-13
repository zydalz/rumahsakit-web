<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Http\Requests\StorePoliRequest;
use Illuminate\Http\Request;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::all();
        return view('admin.poli.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.poli.create');
    }

    public function store(StorePoliRequest $request)
    {
        Poli::create($request->validated());
        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil ditambahkan.');
    }

    public function edit(Poli $poli)
    {
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli)
    {
        $validated = $request->validate([
            'nama_poli' => 'required|string|max:100|unique:poli,nama_poli,' . $poli->id,
            'deskripsi' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $poli->update($validated);
        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();
        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil dihapus.');
    }
}
