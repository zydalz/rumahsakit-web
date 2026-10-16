<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePemeriksaanRequest;
use App\Models\Dokter;
use App\Models\Obat;
use App\Models\Pemeriksaan;
use App\Models\Pendaftaran;
use App\Models\Resep;
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

        $pendaftarans = Pendaftaran::with([
            'pasien.user',
            'pemeriksaan',
            'poli',
        ])
            ->where('poli_id', $dokter->poli_id)
            ->orderByDesc('tanggal_kunjungan')
            ->orderBy('nomor_antrean')
            ->get();

        return view('dokter.pemeriksaan.index', compact(
            'pendaftarans',
            'dokter'
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

        return view('dokter.pemeriksaan.create', compact(
            'pendaftaran',
            'dokter',
            'obats'
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

            if (!empty($daftarObat)) {
                $resep = Resep::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'kode_resep' => 'RSP-' . now()->format('YmdHis')
                        . '-' . Str::upper(Str::random(4)),
                    'catatan_dokter' => $data['catatan'] ?? null,
                    'status' => 'menunggu',
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
                }
            }

            $pendaftaran->update(['status' => 'selesai']);

            return $pemeriksaan;
        });

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