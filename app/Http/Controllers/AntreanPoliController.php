<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dokter;
use App\Models\Pendaftaran;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AntreanPoliController extends Controller
{
    private function dokterLogin(): Dokter
    {
        return Dokter::where('user_id', Auth::id())
            ->where('is_active', true)
            ->firstOrFail();
    }

    /**
     * Memanggil antrean pasien tertentu ke dalam ruang poli.
     */
    public function panggil(Pendaftaran $pendaftaran)
    {
        $dokter = $this->dokterLogin();

        if ((int) $pendaftaran->poli_id !== (int) $dokter->poli_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk poli ini.');
        }

        // Ubah status pasien sebelumnya yang masih dipanggil menjadi dilewati agar fokus ke pasien baru
        Pendaftaran::where('poli_id', $dokter->poli_id)
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->where('status', 'dipanggil')
            ->where('id', '!=', $pendaftaran->id)
            ->update(['status' => 'dilewati']);

        $pendaftaran->update(['status' => 'dipanggil']);

        $namaPasien = $pendaftaran->pasien->nama ?? 'Pasien';
        $nomorAntrean = $pendaftaran->nomor_antrean;

        AuditLog::catat(
            'Pelayanan Poli',
            'Panggil Antrean',
            "Memanggil antrean #{$nomorAntrean} atas nama {$namaPasien} ke ruang " . ($pendaftaran->poli->nama_poli ?? 'Poli')
        );

        return redirect()->back()->with([
            'success' => "Nomor antrean #{$nomorAntrean} atas nama {$namaPasien} sedang dipanggil.",
            'audio_panggil' => [
                'nomor' => $nomorAntrean,
                'nama' => $namaPasien,
                'poli' => $pendaftaran->poli->nama_poli ?? 'Poli',
            ],
        ]);
    }

    /**
     * Memanggil pasien nomor urut berikutnya yang sedang menunggu antrean.
     */
    public function panggilSelanjutnya()
    {
        $dokter = $this->dokterLogin();
        $today = Carbon::today();

        // Cari pasien berikutnya yang sudah dikonfirmasi atau menunggu antrean hari ini
        $pasienBerikutnya = Pendaftaran::with('pasien', 'poli')
            ->where('poli_id', $dokter->poli_id)
            ->whereDate('tanggal_kunjungan', $today)
            ->whereIn('status', ['dikonfirmasi', 'terverifikasi', 'menunggu'])
            ->orderBy('nomor_antrean')
            ->first();

        if (!$pasienBerikutnya) {
            // Cek apakah ada pasien yang sempat dilewati
            $pasienDilewati = Pendaftaran::with('pasien', 'poli')
                ->where('poli_id', $dokter->poli_id)
                ->whereDate('tanggal_kunjungan', $today)
                ->where('status', 'dilewati')
                ->orderBy('nomor_antrean')
                ->first();

            if ($pasienDilewati) {
                return $this->panggil($pasienDilewati);
            }

            return redirect()->back()->with('info', 'Tidak ada pasien dalam antrean yang sedang menunggu saat ini.');
        }

        return $this->panggil($pasienBerikutnya);
    }

    /**
     * Melewati pasien jika belum hadir saat dipanggil.
     */
    public function lewati(Pendaftaran $pendaftaran)
    {
        $dokter = $this->dokterLogin();

        if ((int) $pendaftaran->poli_id !== (int) $dokter->poli_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk poli ini.');
        }

        $pendaftaran->update(['status' => 'dilewati']);

        return redirect()->back()->with(
            'warning',
            "Antrean #{$pendaftaran->nomor_antrean} ({$pendaftaran->pasien->nama}) telah dilewati. Pasien dapat dipanggil kembali kapan saja bila sudah berada di poli."
        );
    }

    /**
     * Memulai pemeriksaan pasien dan mengarahkan langsung ke lembar rekam medis.
     */
    public function mulaiPeriksa(Pendaftaran $pendaftaran)
    {
        $dokter = $this->dokterLogin();

        if ((int) $pendaftaran->poli_id !== (int) $dokter->poli_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk poli ini.');
        }

        if ($pendaftaran->pemeriksaan()->exists()) {
            return redirect()->route('dokter.pemeriksaan.show', $pendaftaran->pemeriksaan->id);
        }

        $pendaftaran->update(['status' => 'sedang_diperiksa']);

        return redirect()->route('dokter.pemeriksaan.create', $pendaftaran->id);
    }

    /**
     * Membatalkan kunjungan pasien oleh dokter jika pasien menolak atau batal periksa.
     */
    public function batalkan(Request $request, Pendaftaran $pendaftaran)
    {
        $dokter = $this->dokterLogin();

        if ((int) $pendaftaran->poli_id !== (int) $dokter->poli_id) {
            abort(403, 'Anda tidak memiliki hak akses untuk poli ini.');
        }

        $request->validate([
            'alasan_pembatalan' => 'required|string|max:500',
        ]);

        $pendaftaran->update([
            'status' => 'dibatalkan',
            'catatan_admin' => 'Dibatalkan oleh ' . ($dokter->nama ?? 'Dokter') . ': ' . $request->alasan_pembatalan,
        ]);

        return redirect()->back()->with('success', "Kunjungan pasien #{$pendaftaran->nomor_antrean} telah dibatalkan.");
    }

    /**
     * Tampilan Layar Monitor Antrean Ruang Tunggu (Bisa dibuka di monitor TV / display poli).
     */
    public function display(Request $request)
    {
        $today = Carbon::today();
        $polis = Poli::where('is_active', true)->with(['dokters.user'])->get();

        $selectedPoliId = $request->query('poli_id');

        $query = Pendaftaran::with(['pasien', 'poli', 'jadwalDokter.dokter'])
            ->whereDate('tanggal_kunjungan', $today);

        if ($selectedPoliId) {
            $query->where('poli_id', $selectedPoliId);
        }

        $pendaftarans = $query->orderBy('nomor_antrean')->get();

        // Pasien yang sedang dipanggil atau sedang diperiksa saat ini
        $antreanSaatIni = $pendaftarans->whereIn('status', ['dipanggil', 'sedang_diperiksa'])->first();

        // Daftar pasien yang masih menunggu giliran berikutnya
        $antreanMenunggu = $pendaftarans->whereIn('status', ['dikonfirmasi', 'terverifikasi', 'menunggu']);

        // Daftar antrean yang dilewati
        $antreanDilewati = $pendaftarans->where('status', 'dilewati');

        // Total dilayani hari ini
        $totalSelesai = $pendaftarans->where('status', 'selesai')->count();

        return view('antrean.display', compact(
            'polis',
            'selectedPoliId',
            'antreanSaatIni',
            'antreanMenunggu',
            'antreanDilewati',
            'totalSelesai',
            'pendaftarans'
        ));
    }
}
