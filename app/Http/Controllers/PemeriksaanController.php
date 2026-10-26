<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemeriksaanRequest;
use App\Models\AuditLog;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Resep;
use App\Models\Tagihan;
use App\Models\DetailTagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PemeriksaanController extends Controller
{
    private function dokterLogin(): Dokter
    {
        return Dokter::where('user_id', Auth::id())
            ->where('is_active', true)
            ->firstOrFail();
    }

    public function index()
    {
        $dokter = $this->dokterLogin();
        $today = \Illuminate\Support\Carbon::today();

        $pendaftarans = Pendaftaran::with([
            'pasien.user',
            'pemeriksaan',
            'poli',
        ])
            ->where('poli_id', $dokter->poli_id)
            ->orderByDesc('tanggal_kunjungan')
            ->orderBy('nomor_antrean')
            ->get();

        // Antrean yang sedang dipanggil atau sedang diperiksa saat ini
        $antreanSaatIni = Pendaftaran::with(['pasien.user', 'poli'])
            ->where('poli_id', $dokter->poli_id)
            ->whereDate('tanggal_kunjungan', $today)
            ->whereIn('status', ['dipanggil', 'sedang_diperiksa'])
            ->orderBy('nomor_antrean')
            ->first();

        $statsAntrean = [
            'total_hari_ini' => $pendaftarans->where('tanggal_kunjungan', $today)->count(),
            'menunggu' => $pendaftarans->where('tanggal_kunjungan', $today)->whereIn('status', ['dikonfirmasi', 'terverifikasi', 'menunggu'])->count(),
            'dilewati' => $pendaftarans->where('tanggal_kunjungan', $today)->where('status', 'dilewati')->count(),
            'selesai' => $pendaftarans->where('tanggal_kunjungan', $today)->where('status', 'selesai')->count(),
        ];

        return view('dokter.pemeriksaan.index', compact(
            'pendaftarans',
            'dokter',
            'antreanSaatIni',
            'statsAntrean'
        ));
    }

    public function create($pendaftaran_id)
    {
        $dokter = $this->dokterLogin();

        $pendaftaran = Pendaftaran::with(['pasien.user', 'poli'])
            ->whereKey($pendaftaran_id)
            ->where('poli_id', $dokter->poli_id)
            ->firstOrFail();

        if ($pendaftaran->pemeriksaan()->exists()) {
            return redirect()->route(
                'dokter.pemeriksaan.show',
                $pendaftaran->pemeriksaan->id
            );
        }

        $obats = Obat::where('is_active', true)
            ->where('stok', '>', 0)
            ->orderBy('nama_obat')
            ->get();

        // Riwayat pemeriksaan sebelumnya untuk membantu dokter melihat riwayat alergi / diagnosis
        $riwayatPemeriksaan = Pemeriksaan::with(['dokter.user', 'resep.detailReseps.obat'])
            ->whereHas('pendaftaran', function ($q) use ($pendaftaran) {
                $q->where('pasien_id', $pendaftaran->pasien_id)
                    ->where('id', '!=', $pendaftaran->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('dokter.pemeriksaan.create', compact(
            'pendaftaran',
            'dokter',
            'obats',
            'riwayatPemeriksaan'
        ));
    }

    public function store(StorePemeriksaanRequest $request)
    {
       \Log::info('DEBUG STORE PEMERIKSAAN TERPANGGIL');

    $dokter = $this->dokterLogin();
        $data = $request->validated();


        $pemeriksaan = DB::transaction(function () use ($data, $dokter) {
            $pendaftaran = Pendaftaran::whereKey($data['pendaftaran_id'])
                ->where('poli_id', $dokter->poli_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($pendaftaran->pemeriksaan()->exists()) {
                abort(409, 'Rekam medis untuk pendaftaran ini sudah tersedia.');
            }

            $pemeriksaan = Pemeriksaan::create([
                'pendaftaran_id' => $pendaftaran->id,
                'dokter_id' => $dokter->id,
                'keluhan' => $data['keluhan'],
                'tekanan_darah' => $data['tekanan_darah'] ?? null,
                'suhu' => $data['suhu'] ?? null,
                'berat_badan' => $data['berat_badan'] ?? null,
                'tinggi_badan' => $data['tinggi_badan'] ?? null,
                'diagnosis' => $data['diagnosis'],
                'catatan' => $data['catatan'] ?? null,
            ]);

            $daftarObat = $data['obat'] ?? [];
            $biayaPemeriksaan = 25000;
            $totalBiayaObat = 0;
            $detailTagihanItems = [
                [
                    'jenis_item' => 'layanan',
                    'deskripsi' => 'Jasa Konsultasi & Pemeriksaan ' . ($pendaftaran->poli->nama_poli ?? 'Dokter'),
                    'jumlah' => 1,
                    'harga_satuan' => $biayaPemeriksaan,
                    'subtotal' => $biayaPemeriksaan,
                ],
            ];

            if (!empty($daftarObat)) {
                $resep = Resep::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'kode_resep' => 'RSP-' . now()->format('YmdHis')
                        . '-' . Str::upper(Str::random(4)),
                    'catatan_dokter' => $data['catatan'] ?? null,
                    'status' => 'menunggu_pembayaran',
                ]);

                foreach ($daftarObat as $item) {
                    $obat = Obat::whereKey($item['obat_id'])
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($obat->stok < (int) $item['jumlah']) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'obat' => "Stok {$obat->nama_obat} tidak mencukupi.",
                        ]);
                    }

                    $resep->detailReseps()->create([
                        'obat_id' => $obat->id,
                        'jumlah' => (int) $item['jumlah'],
                        'dosis' => $item['dosis'],
                        'instruksi_khusus' => $item['instruksi_khusus'] ?? null,
                    ]);

                    $subtotalItem = (float) $obat->harga * (int) $item['jumlah'];
                    $totalBiayaObat += $subtotalItem;

                    $detailTagihanItems[] = [
                        'jenis_item' => 'obat',
                        'deskripsi' => 'Obat: ' . $obat->nama_obat . ' (' . (int) $item['jumlah'] . ' ' . ($obat->satuan ?? 'butir') . ')',
                        'jumlah' => (int) $item['jumlah'],
                        'harga_satuan' => (float) $obat->harga,
                        'subtotal' => $subtotalItem,
                    ];
                }
            }

            // Buat tagihan otomatis jika belum pernah dibuat untuk pendaftaran ini
            if (!Tagihan::where('pendaftaran_id', $pendaftaran->id)->exists()) {
                $totalTagihan = $biayaPemeriksaan + $totalBiayaObat;
                $nomorTagihan = 'TAG-' . now()->format('YmdHis') . '-' . str_pad((string) $pendaftaran->id, 4, '0', STR_PAD_LEFT);

                $tagihan = Tagihan::create([
                    'nomor_tagihan' => $nomorTagihan,
                    'pendaftaran_id' => $pendaftaran->id,
                    'total_tagihan' => $totalTagihan,
                    'status' => 'belum_lunas',
                ]);

                foreach ($detailTagihanItems as $dItem) {
                    $tagihan->detailTagihan()->create($dItem);
                }
            }

            $pendaftaran->update(['status' => 'selesai']);

            return $pemeriksaan;
        });

        AuditLog::catat(
            'Pemeriksaan Medis',
            'Simpan Rekam Medis',
            "Dokter {$dokter->nama_dokter} menyelesaikan pemeriksaan pasien " . ($pemeriksaan->pendaftaran->pasien->nama ?? '-') . " dengan diagnosis: {$pemeriksaan->diagnosis}"
        );

        return redirect()
            ->route('dokter.pemeriksaan.show', $pemeriksaan->id)
            ->with('success', 'Pemeriksaan berhasil disimpan.');
    }

    public function show($id)
    {
        $dokter = $this->dokterLogin();

        $pemeriksaan = Pemeriksaan::with([
            'pendaftaran.pasien.user',
            'pendaftaran.poli',
            'dokter.user',
            'dokter.poli',
        ])
            ->whereKey($id)
            ->whereHas('pendaftaran', function ($query) use ($dokter) {
                $query->where('poli_id', $dokter->poli_id);
            })
            ->firstOrFail();

        return view('dokter.pemeriksaan.show', compact('pemeriksaan'));
    }
}