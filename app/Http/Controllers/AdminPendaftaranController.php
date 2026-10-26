<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AdminPendaftaranController extends Controller
{
    /**
     * Menampilkan daftar pendaftaran rawat jalan untuk diverifikasi admin.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Pendaftaran::with([
            'pasien.user',
            'poli',
            'jadwalDokter.dokter.user',
        ])->latest('tanggal_kunjungan')->latest('id');

        if ($status && in_array($status, ['menunggu_konfirmasi', 'dikonfirmasi', 'sedang_diperiksa', 'selesai', 'dibatalkan'])) {
            $query->where('status', $status);
        }

        $pendaftarans = $query->paginate(15)->withQueryString();

        $counts = [
            'semua' => Pendaftaran::count(),
            'menunggu' => Pendaftaran::where('status', 'menunggu_konfirmasi')->orWhere('status', 'menunggu')->count(),
            'dikonfirmasi' => Pendaftaran::where('status', 'dikonfirmasi')->count(),
            'selesai' => Pendaftaran::where('status', 'selesai')->count(),
            'dibatalkan' => Pendaftaran::where('status', 'dibatalkan')->count(),
        ];

        return view('admin.pendaftaran.index', compact('pendaftarans', 'counts', 'status'));
    }

    /**
     * Konfirmasi ketersediaan dokter dan jadwal oleh Admin.
     */
    public function konfirmasi(Pendaftaran $pendaftaran, Request $request)
    {
        $pendaftaran->update([
            'status' => 'dikonfirmasi',
            'status_verifikasi' => 'terverifikasi',
            'catatan_admin' => $request->input('catatan_admin') ?? 'Kunjungan dikonfirmasi oleh admin. Silakan hadir sesuai jam estimasi.',
        ]);

        return redirect()->back()->with('success', "Pendaftaran {$pendaftaran->kode_pendaftaran} berhasil dikonfirmasi. Pasien siap dilayani.");
    }

    /**
     * Batalkan pendaftaran (misal dokter berhalangan atau kuota penuh).
     */
    public function batalkan(Pendaftaran $pendaftaran, Request $request)
    {
        $validated = $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ], [
            'catatan_admin.required' => 'Wajib menyertakan alasan pembatalan untuk pasien.',
        ]);

        $pendaftaran->update([
            'status' => 'dibatalkan',
            'status_verifikasi' => 'dibatalkan',
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return redirect()->back()->with('success', "Pendaftaran {$pendaftaran->kode_pendaftaran} telah dibatalkan.");
    }
}
