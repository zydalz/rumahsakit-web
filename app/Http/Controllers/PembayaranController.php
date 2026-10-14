<?php

namespace App\Http\Controllers;

use App\Models\Tagihan;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PembayaranController extends Controller
{
    public function store(Request $request, Tagihan $tagihan)
    {
        $request->validate([
            'jumlah_bayar' => ['required', 'numeric', 'min:0.01'],
            'metode' => ['required', 'string', 'max:20'],
            'referensi' => ['nullable', 'string', 'max:100'],
        ]);

        if ($tagihan->status === 'lunas') {
            return back()->with('error', 'Tagihan ini sudah lunas.');
        }

        // Muat relasi pembayaran jika belum dimuat
        $tagihan->load('pembayaran');

        DB::beginTransaction();
        try {
           // Catat pembayaran baru
            Pembayaran::create([
                'tagihan_id' => $tagihan->getKey(),
                'kasir_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
                'tanggal_bayar' => Carbon::now(),
                'metode' => $request->metode,
                'jumlah_bayar' => $request->jumlah_bayar,
                'status' => 'berhasil',
                'referensi' => $request->referensi,
            ]);

            // Hitung total akumulasi pembayaran berhasil
            $totalBayarBerhasil = $tagihan->pembayaran()->where('status', 'berhasil')->sum('jumlah_bayar') + $request->jumlah_bayar;

            // Perbarui status tagihan jika lunas
            if ($totalBayarBerhasil >= $tagihan->total_tagihan) {
                $tagihan->update(['status' => 'lunas']);
            }

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil dicatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }
}