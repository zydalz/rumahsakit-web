<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\User;
use App\Http\Requests\StoreDokterRequest;
use Illuminate\Http\Request;

class DokterController extends Controller
{
    public function index()
    {
        $dokters = Dokter::with(['user', 'poli'])->latest()->get();
        return view('admin.dokter.index', compact('dokters'));
    }

    public function create()
    {
        $polis = Poli::where('is_active', true)->orderBy('nama_poli')->get();
        $users = User::where('role', 'dokter')->whereDoesntHave('dokter')->orderBy('name')->get();
        return view('admin.dokter.create', compact('polis', 'users'));
    }

    public function store(StoreDokterRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Dokter::create($data);
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    public function edit(Dokter $dokter)
    {
        $polis = Poli::where('is_active', true)->orderBy('nama_poli')->get();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }

    public function update(Request $request, Dokter $dokter)
    {
        $validated = $request->validate([
            'poli_id' => 'required|exists:poli,id',
            'nomor_sip' => 'nullable|string|max:50',
            'no_telepon' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);
        $validated['is_active'] = $request->boolean('is_active');

        $dokter->update($validated);
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy(Dokter $dokter)
    {
        if ($dokter->pemeriksaans()->exists() || $dokter->jadwalDokters()->exists()) {
            return redirect()->route('admin.dokter.index')->with('error', 'Dokter tidak dapat dihapus karena sudah memiliki riwayat jadwal atau pemeriksaan.');
        }

        $dokter->delete();
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }
}