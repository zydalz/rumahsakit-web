<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard sesuai role pengguna.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'kasir' => $this->kasirDashboard(),
            'pasien' => $this->pasienDashboard($user),
            'dokter' => redirect()->route('dokter.dashboard'),
            'farmasi' => redirect()->route('farmasi.index'),
            default => abort(403, 'Role pengguna belum memiliki dashboard.'),
        };
    }

    /**
     * Dashboard dan Laporan Ringkas untuk Admin
     */
    protected function adminDashboard()
    {
        $stats = [
            'total_pasien' => Pasien::count(),
            'kunjungan_hari_ini' => Pendaftaran::whereDate('tanggal_kunjungan', today())->count(),
            'obat_stok_kritis' => Obat::where('stok', '<=', DB::raw('stok_minimum'))->count(),
            'total_pendapatan' => (float) Pembayaran::where('status', 'berhasil')->sum('jumlah_bayar'),
            'total_dokter' => Dokter::where('is_active', true)->count(),
            'total_poli' => Poli::where('is_active', true)->count(),
        ];

        $pendaftaranTerbaru = Pendaftaran::with(['pasien', 'poli', 'jadwalDokter.dokter.user'])
            ->latest()
            ->take(6)
            ->get();

        $obatKritis = Obat::where('stok', '<=', DB::raw('stok_minimum'))
            ->orderBy('stok')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendaftaranTerbaru', 'obatKritis'));
    }

    /**
     * Dashboard dan Laporan Finansial Kasir
     */
    protected function kasirDashboard()
    {
        $penerimaanHariIni = (float) Pembayaran::where('status', 'berhasil')
            ->whereDate('tanggal_bayar', today())
            ->sum('jumlah_bayar');

        $transaksiHariIni = Pembayaran::where('status', 'berhasil')
            ->whereDate('tanggal_bayar', today())
            ->count();

        $tagihanBelumLunas = Tagihan::where('status', 'belum_lunas')->count();

        // Hitung total sisa piutang tagihan yang belum lunas
        $tagihans = Tagihan::with('pembayaran')->where('status', '!=', 'dibatalkan')->get();
        $totalPiutang = $tagihans->sum(fn ($tagihan) => $tagihan->sisa_tagihan);

        $stats = [
            'penerimaan_hari_ini' => $penerimaanHariIni,
            'transaksi_hari_ini' => $transaksiHariIni,
            'tagihan_belum_lunas' => $tagihanBelumLunas,
            'total_piutang' => $totalPiutang,
        ];

        $tagihanPerluBayar = Tagihan::with(['pendaftaran.pasien', 'pembayaran'])
            ->where('status', 'belum_lunas')
            ->latest()
            ->take(6)
            ->get();

        $transaksiTerbaru = Pembayaran::with(['tagihan.pendaftaran.pasien', 'kasir'])
            ->where('status', 'berhasil')
            ->latest('tanggal_bayar')
            ->take(6)
            ->get();

        return view('kasir.dashboard', compact('stats', 'tagihanPerluBayar', 'transaksiTerbaru'));
    }

    /**
     * Dashboard Pasien dengan Kunjungan dan Tagihan Terintegrasi
     */
    protected function pasienDashboard($user)
    {
        $pasien = $user->pasien;
        $kunjunganHariIni = null;
        $kunjunganMendatang = collect();
        $riwayatKunjungan = collect();
        $tagihanPasien = collect();

        if ($pasien) {
            $kunjunganHariIni = Pendaftaran::with(['poli', 'jadwalDokter.dokter.user'])
                ->where('pasien_id', $pasien->id)
                ->whereDate('tanggal_kunjungan', today())
                ->latest()
                ->first();

            $kunjunganMendatang = Pendaftaran::with(['poli', 'jadwalDokter.dokter.user'])
                ->where('pasien_id', $pasien->id)
                ->whereDate('tanggal_kunjungan', '>', today())
                ->orderBy('tanggal_kunjungan')
                ->take(3)
                ->get();

            $riwayatKunjungan = Pendaftaran::with(['poli', 'pemeriksaan.dokter.user', 'tagihan'])
                ->where('pasien_id', $pasien->id)
                ->latest('tanggal_kunjungan')
                ->take(5)
                ->get();

            $tagihanPasien = Tagihan::with(['pembayaran', 'pendaftaran'])
                ->whereHas('pendaftaran', fn ($q) => $q->where('pasien_id', $pasien->id))
                ->latest()
                ->take(3)
                ->get();
        }

        return view('dashboard', compact(
            'user',
            'pasien',
            'kunjunganHariIni',
            'kunjunganMendatang',
            'riwayatKunjungan',
            'tagihanPasien'
        ));
    }
}
