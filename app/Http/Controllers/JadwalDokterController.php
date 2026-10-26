<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\Dokter;
use App\Http\Requests\StoreJadwalDokterRequest;

class JadwalDokterController extends Controller
{
    public function index()
    {
        $jadwals = JadwalDokter::with('dokter.user', 'dokter.poli')->latest()->get();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        $dokters = Dokter::with('user', 'poli')->where('is_active', true)->get();
        return view('admin.jadwal.create', compact('dokters'));
    }

    public function store(StoreJadwalDokterRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        JadwalDokter::create($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil ditambahkan.');
    }

    public function edit(JadwalDokter $jadwal)
    {
        $dokters = Dokter::with('user', 'poli')->where('is_active', true)->get();
        return view('admin.jadwal.edit', compact('jadwal', 'dokters'));
    }

    public function update(StoreJadwalDokterRequest $request, JadwalDokter $jadwal)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $jadwal->update($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil diperbarui.');
    }

    public function destroy(JadwalDokter $jadwal)
    {
        if ($jadwal->pendaftarans()->exists()) {
            return redirect()->route('admin.jadwal.index')->with('error', 'Jadwal dokter tidak dapat dihapus karena sudah memiliki riwayat pendaftaran pasien.');
        }

        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal dokter berhasil dihapus.');
    }
}
