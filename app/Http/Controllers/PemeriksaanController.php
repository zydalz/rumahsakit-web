<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemeriksaanRequest;
use App\Models\Dokter;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PemeriksaanController extends Controller
{
    /**
     * Menampilkan daftar antrean dan kunjungan pasien di Poli Dokter.
     */
    public function index()
    {
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();

        $pendaftarans = Pendaftaran::with(['pasien.user', 'pemeriksaan'])
            ->where('poli_id', $dokter->poli_id)
            ->orderBy('tanggal_kunjungan', 'desc')
            ->orderBy('nomor_antrean', 'asc')
            ->get();

        return view('dokter.pemeriksaan.index', compact('pendaftarans', 'dokter'));
    }

    /**
     * Menampilkan form input rekam medis.
     */
    public function create($pendaftaran_id)
    {
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();

        $pendaftaran = Pendaftaran::with(['pasien.user'])
            ->where('id', $pendaftaran_id)
            ->where('poli_id', $dokter->poli_id)
            ->firstOrFail();

        return view('dokter.pemeriksaan.create', compact('pendaftaran', 'dokter'));
    }

    /**
     * Menyimpan data rekam medis dan memperbarui status pendaftaran.
     */
    public function store(StorePemeriksaanRequest $request)
    {
        $dokter = Dokter::where('user_id', Auth::id())->firstOrFail();

        // Menggunakan Database Transaction untuk menjamin integritas data
        DB::transaction(function () use ($request, $dokter) {
            // 1. Simpan Rekam Medis Pasien
            Pemeriksaan::create([
                'pendaftaran_id' => $request->pendaftaran_id,
                'dokter_id'      => $dokter->id,
                'keluhan'        => $request->keluhan,
                'tekanan_darah'  => $request->tekanan_darah,
                'suhu'           => $request->suhu,
                'berat_badan'    => $request->berat_badan,
                'tinggi_badan'   => $request->tinggi_badan,
                'diagnosis'      => $request->diagnosis,
                'catatan'        => $request->catatan,
            ]);

            // 2. Otomatis perbarui status pendaftaran menjadi 'selesai'
            $pendaftaran = Pendaftaran::findOrFail($request->pendaftaran_id);
            $pendaftaran->update([
                'status' => 'selesai'
            ]);
        });

        return redirect()
            ->route('dokter.pemeriksaan.index')
            ->with('success', 'Rekam medis pasien berhasil disimpan dan status kunjungan telah diperbarui!');
    }

    /**
     * Menampilkan detail rekam medis pasien.
     */
    public function show($id)
    {
        $pemeriksaan = Pemeriksaan::with(['pendaftaran.pasien.user', 'dokter.user', 'dokter.poli'])
            ->findOrFail($id);

        return view('dokter.pemeriksaan.show', compact('pemeriksaan'));
    }
}
