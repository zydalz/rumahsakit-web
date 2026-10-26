<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resep;
use App\Models\DetailResep;

class FarmasiController extends Controller
{
    // Menampilkan daftar resep di bagian farmasi
    public function index()
    {
        // Mengambil data resep beserta relasi ke pendaftaran pasien
        $reseps = Resep::with('pendaftaran', 'detailReseps')->latest()->get();

        $stats = [
            'total_resep' => $reseps->count(),
            'resep_menunggu' => $reseps->where('status', 'menunggu')->count(),
            'resep_diproses' => $reseps->where('status', 'diproses')->count(),
            'resep_selesai' => $reseps->where('status', 'selesai')->count(),
            'obat_kritis' => \App\Models\Obat::where('stok', '<=', \Illuminate\Support\Facades\DB::raw('stok_minimum'))->count(),
        ];
        
        return view('farmasi.index', compact('reseps', 'stats'));
    }

    // Mengubah status resep (misal: dari menunggu jadi diproses atau selesai)
    public function updateStatus(Request $request, $id)
    {
        $resep = Resep::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:menunggu,diproses,selesai',
        ]);

        $resep->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status resep berhasil diperbarui!');
    }
}