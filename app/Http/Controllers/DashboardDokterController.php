<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Pendaftaran;
use App\Models\Pemeriksaan;

class DashboardDokterController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $dokter = $user->dokter;

        $totalPasienDiperiksa = 0;
        $antreanHariIni = 0;

        if ($dokter) {
            $totalPasienDiperiksa = Pemeriksaan::where('dokter_id', $dokter->id)->count();
            $antreanHariIni = Pendaftaran::where('poli_id', $dokter->poli_id)
                ->whereDate('tanggal_kunjungan', today())
                ->count();
        }

        return view('dokter.dashboard', compact('dokter', 'totalPasienDiperiksa', 'antreanHariIni'));
    }
}