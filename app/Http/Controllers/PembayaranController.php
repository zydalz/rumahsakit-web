<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Tagihan;
use App\Models\Pembayaran;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PembayaranController extends Controller
{
    public function store(Request $request, Tagihan $tagihan)
    {
        $validated = $request->validate([
            'jumlah_bayar' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],
            'metode' => [
                'required',
                'in:tunai,transfer,qris',
            ],
            'referensi' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        if (!Auth::check()) {
            abort(401);
        }

        DB::transaction(function () use ($tagihan, $validated) {
            $tagihan = Tagihan::query()
                ->lockForUpdate()
                ->findOrFail($tagihan->id);

            if ($tagihan->status === 'lunas') {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Tagihan ini sudah lunas.',
                ]);
            }

            if ($tagihan->status === 'dibatalkan') {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Tagihan yang dibatalkan tidak dapat dibayar.',
                ]);
            }

            $totalBayar = (float) $tagihan->pembayaran()
                ->where('status', 'berhasil')
                ->sum('jumlah_bayar');

            $sisaTagihan = max(
                0,
                (float) $tagihan->total_tagihan - $totalBayar
            );

            $jumlahBayar = (float) $validated['jumlah_bayar'];

            if ($jumlahBayar > $sisaTagihan) {
                throw ValidationException::withMessages([
                    'jumlah_bayar' => 'Pembayaran melebihi sisa tagihan Rp'
                        . number_format($sisaTagihan, 0, ',', '.')
                        . '.',
                ]);
            }

            Pembayaran::create([
                'tagihan_id' => $tagihan->id,
                'kasir_id' => Auth::id(),
                'tanggal_bayar' => now(),
                'metode' => $validated['metode'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'status' => 'berhasil',
                'referensi' => $validated['referensi'] ?? null,
            ]);

            $totalBayar += $jumlahBayar;
            $isLunas = $totalBayar >= (float) $tagihan->total_tagihan;

            $tagihan->update([
                'status' => $isLunas ? 'lunas' : 'belum_lunas',
            ]);

            if ($isLunas && $tagihan->pendaftaran_id) {
                Resep::where('pendaftaran_id', $tagihan->pendaftaran_id)
                    ->whereIn('status', ['menunggu', 'menunggu_pembayaran'])
                    ->update(['status' => 'siap_diserahkan']);
            }
        });

        AuditLog::catat(
            'Kasir & Tagihan',
            'Pencatatan Pembayaran',
            "Kasir mencatat pembayaran Rp " . number_format($validated['jumlah_bayar'], 0, ',', '.') . " via {$validated['metode']} untuk tagihan {$tagihan->nomor_tagihan}"
        );

        return redirect()
            ->route('tagihan.show', $tagihan->id)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }
    
public function nota(Tagihan $tagihan, Pembayaran $pembayaran)
{
    // Pastikan pembayaran memang milik tagihan yang dipilih.
    abort_unless(
        (int) $pembayaran->tagihan_id === (int) $tagihan->id,
        404
    );

    // Nota hanya tersedia untuk transaksi yang berhasil.
    abort_unless($pembayaran->status === 'berhasil', 404);

    $tagihan->load([
        'pendaftaran.pasien',
        'detailTagihan',
        'pembayaran',
    ]);

    $totalDibayar = (float) $tagihan->pembayaran
        ->where('status', 'berhasil')
        ->sum('jumlah_bayar');

    $sisaTagihan = max(
        0,
        (float) $tagihan->total_tagihan - $totalDibayar
    );

    return view('tagihan.nota', compact(
        'tagihan',
        'pembayaran',
        'totalDibayar',
        'sisaTagihan'
    ));
}

}
