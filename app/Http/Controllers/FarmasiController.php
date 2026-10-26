<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\DetailResep;
use App\Models\Obat;
use App\Models\Resep;
use App\Models\RiwayatStokObat;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class FarmasiController extends Controller
{
    // Menampilkan daftar resep di bagian farmasi
    public function index()
    {
        // Mengambil data resep beserta relasi ke pendaftaran, tagihan, dan detail obat
        $reseps = Resep::with([
            'pendaftaran.pasien.user',
            'pendaftaran.tagihan.pembayaran',
            'pendaftaran.poli',
            'detailReseps.obat',
        ])->latest()->get();

        $stats = [
            'total_resep' => $reseps->count(),
            'resep_menunggu' => $reseps->whereIn('status', ['menunggu', 'menunggu_pembayaran'])->count(),
            'resep_siap' => $reseps->whereIn('status', ['diproses', 'siap_diserahkan'])->count(),
            'resep_selesai' => $reseps->where('status', 'selesai')->count(),
            'obat_kritis' => Obat::where('stok', '<=', DB::raw('stok_minimum'))->count(),
        ];

        return view('farmasi.index', compact('reseps', 'stats'));
    }

    // Mengubah status resep (menyerahkan obat atau mengubah status antrean resep)
    public function updateStatus(Request $request, $id)
    {
        $resep = Resep::with(['detailReseps.obat', 'pendaftaran.pasien'])->findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,menunggu_pembayaran,diproses,siap_diserahkan,selesai',
        ]);

        $statusBaru = $request->status;

        // Cegah perubahan status bila resep sudah diselesaikan agar stok tidak terpotong ganda
        if ($resep->status === 'selesai') {
            return redirect()->back()->with('error', 'Resep ini sudah selesai dan obat telah diserahkan sebelumnya.');
        }

        // Jika menyerahkan obat (status = selesai), pastikan tagihan kasir telah lunas
        if ($statusBaru === 'selesai') {
            $tagihan = Tagihan::where('pendaftaran_id', $resep->pendaftaran_id)->first();

            if ($tagihan && $tagihan->status !== 'lunas') {
                return redirect()->back()->with('error', 'Obat belum dapat diserahkan karena tagihan pasien di kasir belum lunas.');
            }

            // Proses pemotongan stok obat dan pencatatan riwayat inventaris
            DB::transaction(function () use ($resep) {
                foreach ($resep->detailReseps as $detail) {
                    $obat = Obat::whereKey($detail->obat_id)->lockForUpdate()->first();

                    if (!$obat) {
                        continue;
                    }

                    if ($obat->stok < $detail->jumlah) {
                        throw ValidationException::withMessages([
                            'stok' => "Stok obat {$obat->nama_obat} tidak mencukupi (sisa {$obat->stok}, dibutuhkan {$detail->jumlah}).",
                        ]);
                    }

                    $stokSebelum = $obat->stok;
                    $stokSesudah = $stokSebelum - $detail->jumlah;

                    $obat->update(['stok' => $stokSesudah]);

                    RiwayatStokObat::create([
                        'obat_id' => $obat->id,
                        'user_id' => Auth::id() ?? 1,
                        'jenis_perubahan' => 'keluar',
                        'jumlah' => $detail->jumlah,
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'keterangan' => 'Penyerahan resep ' . $resep->kode_resep . ' pasien ' . ($resep->pendaftaran->pasien->nama ?? '-'),
                    ]);
                }

                $resep->update(['status' => 'selesai']);
            });

            AuditLog::catat(
                'Farmasi',
                'Penyerahan Obat',
                "Petugas farmasi menyerahkan obat resep {$resep->kode_resep} kepada pasien " . ($resep->pendaftaran->pasien->nama ?? '-') . " dan memotong stok obat otomatis"
            );

            return redirect()->back()->with('success', 'Obat berhasil diserahkan kepada pasien dan persediaan stok obat telah terpotong otomatis.');
        }

        // Perubahan status antrean lainnya (misal: siap_diserahkan atau diproses)
        $resep->update(['status' => $statusBaru]);

        AuditLog::catat(
            'Farmasi',
            'Pembaruan Status Resep',
            "Status resep {$resep->kode_resep} diubah menjadi '{$statusBaru}' oleh farmasi"
        );

        return redirect()->back()->with('success', 'Status resep berhasil diperbarui!');
    }
}