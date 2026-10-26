<?php

namespace App\Http\Controllers;

use App\Models\DetailResep;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Pembayaran;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Poli;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class KepalaRumahSakitController extends Controller
{
    /**
     * Dashboard Eksekutif untuk Kepala Rumah Sakit
     */
    public function dashboard()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // 1. Metrik Keuangan Kasir
        $pendapatanHariIni = (float) Pembayaran::where('status', 'berhasil')
            ->whereDate('tanggal_bayar', $today)
            ->sum('jumlah_bayar');

        $pendapatanBulanIni = (float) Pembayaran::where('status', 'berhasil')
            ->where('tanggal_bayar', '>=', $startOfMonth)
            ->sum('jumlah_bayar');

        $pendapatanTahunIni = (float) Pembayaran::where('status', 'berhasil')
            ->where('tanggal_bayar', '>=', $startOfYear)
            ->sum('jumlah_bayar');

        $totalPendapatan = (float) Pembayaran::where('status', 'berhasil')
            ->sum('jumlah_bayar');

        // Total piutang yang belum lunas
        $tagihansBelumLunas = Tagihan::with('pembayaran')
            ->where('status', 'belum_lunas')
            ->get();
        $totalPiutang = $tagihansBelumLunas->sum(fn ($t) => $t->sisa_tagihan);

        // 2. Metrik Kunjungan Pasien
        $kunjunganHariIni = Pendaftaran::whereDate('tanggal_kunjungan', $today)->count();
        $kunjunganBulanIni = Pendaftaran::where('tanggal_kunjungan', '>=', $startOfMonth)->count();
        $totalPasienTerdaftar = Pasien::count();

        // 3. Status Layanan & Obat
        $dokterAktif = Dokter::where('is_active', true)->count();
        $poliAktif = Poli::where('is_active', true)->count();
        $obatKritisCount = Obat::where('stok', '<=', DB::raw('stok_minimum'))->count();
        $totalItemObat = Obat::where('is_active', true)->count();

        // 4. Sebaran Kunjungan Pasien per Poliklinik (Bulan Ini)
        $sebaranPoli = Poli::withCount(['pendaftarans' => function ($q) use ($startOfMonth) {
            $q->where('tanggal_kunjungan', '>=', $startOfMonth);
        }])->get();

        // 5. Tren 5 Penyakit Terbanyak Bulan Ini
        $topPenyakit = Pemeriksaan::select('diagnosis', DB::raw('count(*) as total'))
            ->where('created_at', '>=', $startOfMonth)
            ->groupBy('diagnosis')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // 6. Transaksi Pembayaran Kasir Terkini
        $transaksiTerbaru = Pembayaran::with(['tagihan.pendaftaran.pasien', 'kasir'])
            ->where('status', 'berhasil')
            ->latest('tanggal_bayar')
            ->take(6)
            ->get();

        return view('kepala_rs.dashboard', compact(
            'pendapatanHariIni',
            'pendapatanBulanIni',
            'pendapatanTahunIni',
            'totalPendapatan',
            'totalPiutang',
            'kunjunganHariIni',
            'kunjunganBulanIni',
            'totalPasienTerdaftar',
            'dokterAktif',
            'poliAktif',
            'obatKritisCount',
            'totalItemObat',
            'sebaranPoli',
            'topPenyakit',
            'transaksiTerbaru'
        ));
    }

    /**
     * Halaman Rekapitulasi Laporan Kinerja & Pelayanan Rumah Sakit
     */
    public function laporan(Request $request)
    {
        $periode = $request->query('periode', 'bulan_ini');
        $tanggalMulaiInput = $request->query('tanggal_mulai');
        $tanggalSelesaiInput = $request->query('tanggal_selesai');

        // Tentukan rentang tanggal berdasarkan filter periode
        $now = Carbon::now();
        if ($periode === 'hari_ini') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            $labelPeriode = 'Hari Ini (' . $now->translatedFormat('d F Y') . ')';
        } elseif ($periode === 'tahun_ini') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfYear();
            $labelPeriode = 'Tahun ' . $now->year;
        } elseif ($periode === 'custom' && $tanggalMulaiInput && $tanggalSelesaiInput) {
            $startDate = Carbon::parse($tanggalMulaiInput)->startOfDay();
            $endDate = Carbon::parse($tanggalSelesaiInput)->endOfDay();
            $labelPeriode = $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
        } else {
            // Default: Bulan Ini
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $labelPeriode = 'Bulan ' . $now->translatedFormat('F Y');
            $periode = 'bulan_ini';
        }

        // 1. Data Rekapitulasi Keuangan & Kasir
        $rekapPembayaran = Pembayaran::where('status', 'berhasil')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->select('metode', DB::raw('sum(jumlah_bayar) as total_nominal'), DB::raw('count(*) as jumlah_transaksi'))
            ->groupBy('metode')
            ->get();

        $totalPendapatanPeriode = $rekapPembayaran->sum('total_nominal');
        $totalTransaksiPeriode = $rekapPembayaran->sum('jumlah_transaksi');

        // 2. Data Rekapitulasi Kunjungan Pasien
        $totalKunjunganPeriode = Pendaftaran::whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $kunjunganSelesai = Pendaftaran::whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'selesai')
            ->count();
        $kunjunganBatal = Pendaftaran::whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'dibatalkan')
            ->count();

        // Kunjungan per Poli
        $kunjunganPerPoli = Poli::withCount(['pendaftarans' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()]);
        }])->get();

        // 3. Top 10 Diagnosa Penyakit Terbanyak (Tren Morbiditas)
        $topDiagnosa = Pemeriksaan::select('diagnosis', DB::raw('count(*) as total_kasus'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('diagnosis')
            ->orderByDesc('total_kasus')
            ->take(10)
            ->get();

        // 4. Kinerja Dokter (Jumlah Pasien Ditangani)
        $kinerjaDokter = Dokter::with(['user', 'poli'])
            ->withCount(['pemeriksaans' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderByDesc('pemeriksaans_count')
            ->get();

        // 5. Obat Paling Sering Diresepkan
        $topObat = DetailResep::with('obat')
            ->whereHas('resep', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('obat_id', DB::raw('sum(jumlah) as total_pengeluaran'), DB::raw('count(*) as total_resep'))
            ->groupBy('obat_id')
            ->orderByDesc('total_pengeluaran')
            ->take(10)
            ->get();

        return view('kepala_rs.laporan', compact(
            'periode',
            'startDate',
            'endDate',
            'labelPeriode',
            'totalPendapatanPeriode',
            'totalTransaksiPeriode',
            'rekapPembayaran',
            'totalKunjunganPeriode',
            'kunjunganSelesai',
            'kunjunganBatal',
            'kunjunganPerPoli',
            'topDiagnosa',
            'kinerjaDokter',
            'topObat'
        ));
    }

    /**
     * Tampilan Cetak Laporan Eksekutif (Print View)
     */
    public function cetakLaporan(Request $request)
    {
        // Panggil logika yang sama dengan halaman laporan, tampilkan blade khusus print
        $periode = $request->query('periode', 'bulan_ini');
        $tanggalMulaiInput = $request->query('tanggal_mulai');
        $tanggalSelesaiInput = $request->query('tanggal_selesai');

        $now = Carbon::now();
        if ($periode === 'hari_ini') {
            $startDate = Carbon::today()->startOfDay();
            $endDate = Carbon::today()->endOfDay();
            $labelPeriode = 'Hari Ini (' . $now->translatedFormat('d F Y') . ')';
        } elseif ($periode === 'tahun_ini') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfYear();
            $labelPeriode = 'Tahun ' . $now->year;
        } elseif ($periode === 'custom' && $tanggalMulaiInput && $tanggalSelesaiInput) {
            $startDate = Carbon::parse($tanggalMulaiInput)->startOfDay();
            $endDate = Carbon::parse($tanggalSelesaiInput)->endOfDay();
            $labelPeriode = $startDate->translatedFormat('d M Y') . ' s/d ' . $endDate->translatedFormat('d M Y');
        } else {
            $startDate = $now->copy()->startOfMonth();
            $endDate = $now->copy()->endOfMonth();
            $labelPeriode = 'Bulan ' . $now->translatedFormat('F Y');
            $periode = 'bulan_ini';
        }

        $rekapPembayaran = Pembayaran::where('status', 'berhasil')
            ->whereBetween('tanggal_bayar', [$startDate, $endDate])
            ->select('metode', DB::raw('sum(jumlah_bayar) as total_nominal'), DB::raw('count(*) as jumlah_transaksi'))
            ->groupBy('metode')
            ->get();

        $totalPendapatanPeriode = $rekapPembayaran->sum('total_nominal');
        $totalTransaksiPeriode = $rekapPembayaran->sum('jumlah_transaksi');

        $totalKunjunganPeriode = Pendaftaran::whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])->count();
        $kunjunganSelesai = Pendaftaran::whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()])
            ->where('status', 'selesai')
            ->count();

        $kunjunganPerPoli = Poli::withCount(['pendaftarans' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal_kunjungan', [$startDate->toDateString(), $endDate->toDateString()]);
        }])->get();

        $topDiagnosa = Pemeriksaan::select('diagnosis', DB::raw('count(*) as total_kasus'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('diagnosis')
            ->orderByDesc('total_kasus')
            ->take(10)
            ->get();

        $kinerjaDokter = Dokter::with(['user', 'poli'])
            ->withCount(['pemeriksaans' => function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->orderByDesc('pemeriksaans_count')
            ->get();

        $topObat = DetailResep::with('obat')
            ->whereHas('resep', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('obat_id', DB::raw('sum(jumlah) as total_pengeluaran'), DB::raw('count(*) as total_resep'))
            ->groupBy('obat_id')
            ->orderByDesc('total_pengeluaran')
            ->take(10)
            ->get();

        return view('kepala_rs.cetak_laporan', compact(
            'periode',
            'labelPeriode',
            'startDate',
            'endDate',
            'totalPendapatanPeriode',
            'totalTransaksiPeriode',
            'rekapPembayaran',
            'totalKunjunganPeriode',
            'kunjunganSelesai',
            'kunjunganPerPoli',
            'topDiagnosa',
            'kinerjaDokter',
            'topObat'
        ));
    }
}
