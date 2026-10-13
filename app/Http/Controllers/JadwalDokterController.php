<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\Dokter;
use App\Http\Requests\StoreJadwalDokterRequest;

class JadwalDokterController extends Controller
{
    public function index()
    {
        $jadwals = JadwalDokter::with('dokter.user', 'dokter.poli')->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $dokters = Dokter::with('user', 'poli')->where('is_active', true)->get();
        return view('admin.jadwal.create', compact('dokters'));
    }

    public function store(StoreJadwalDokterRequest $request)
    {
        JadwalDokter::create($request->validated());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function edit(JadwalDokter $jadwal)
    {
        $dokters = Dokter::with('user', 'poli')->where('is_active', true)->get();
        return view('admin.jadwal.edit', compact('jadwal', 'dokters'));
    }

    public function update(StoreJadwalDokterRequest $request, JadwalDokter $jadwal)
    {
        $jadwal->update($request->validated());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(JadwalDokter $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
