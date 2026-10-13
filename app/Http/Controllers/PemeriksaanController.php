<?php

namespace App\Http\Controllers;

use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Http\Requests\StorePemeriksaanRequest;
use Illuminate\Support\Facades\Auth;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dokter = $user->dokter;

        if (!$dokter) {
            return redirect()->back()->with('error', 'Akun Anda tidak terhubung dengan data dokter.');
        }

        $pendaftarans = Pendaftaran::with(['pasien', 'poli', 'jadwalDokter', 'pemeriksaan'])
            ->where('poli_id', $dokter->poli_id)
            ->orderBy('tanggal_kunjungan', 'desc')
            ->get();

        return view('dokter.pemeriksaan.index', compact('pendaftarans', 'dokter'));
    }

    public function create(Pendaftaran $pendaftaran)
    {
        $user = Auth::user();
        $dokter = $user->dokter;

        return view('dokter.pemeriksaan.create', compact('pendaftaran', 'dokter'));
    }

    public function store(StorePemeriksaanRequest $request)
    {
        Pemeriksaan::create($request->validated());

        $pendaftaran = Pendaftaran::find($request->pendaftaran_id);
        if ($pendaftaran) {
            $pendaftaran->update(['status' => 'selesai']);
        }

        return redirect()->route('dokter.pemeriksaan.index')->with('success', 'Pemeriksaan dan rekam medis berhasil disimpan.');
    }

    public function show(Pemeriksaan $pemeriksaan)
    {
        $pemeriksaan->load(['pendaftaran.pasien', 'pendaftaran.poli', 'dokter.user']);
        return view('dokter.pemeriksaan.show', compact('pemeriksaan'));
    }
}
