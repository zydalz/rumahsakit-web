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
        
        return view('farmasi.index', compact('reseps'));
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