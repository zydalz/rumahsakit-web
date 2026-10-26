<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Poli;
use App\Models\JadwalDokter;
use App\Models\Dokter;

class PendaftaranController extends Controller
{
    /**
     * Menampilkan riwayat pendaftaran pasien yang login.
     */
    public function index(): View
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            abort(403, 'Profil pasien tidak ditemukan.');
        }

        $pendaftarans = Pendaftaran::with(['poli', 'jadwalDokter'])
            ->where('pasien_id', $pasien->id)
            ->orderByDesc('tanggal_kunjungan')
            ->orderByDesc('id')
            ->paginate(10);

        return view('pendaftaran.index', compact('pendaftarans'));
    }

    
    /**
     * Menampilkan formulir pendaftaran.
     */
    
    public function create(): View
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            abort(403, 'Profil pasien tidak ditemukan.');
        }

        $polis = Poli::where('is_active', true)
            ->orderBy('nama_poli')
            ->get();

        $jadwals = JadwalDokter::with(['dokter.user', 'dokter.poli'])
            ->where('is_active', true)
            ->whereHas('dokter', function ($query) {
                $query->where('is_active', true)
                    ->whereHas('poli', function ($poliQuery) {
                        $poliQuery->where('is_active', true);
                    });
            })
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();

        return view('pendaftaran.create', compact('polis', 'jadwals'));
    }


    /**
     * Menyimpan pendaftaran rawat jalan.
     */
    public function store(Request $request)
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien) {
            abort(403, 'Profil pasien tidak ditemukan.');
        }

        $validated = $request->validate([
            'poli_id' => [
                'required',
                'integer',
                Rule::exists('poli', 'id')->where('is_active', true),
            ],
            'jadwal_dokter_id' => [
                'required',
                'integer',
                Rule::exists('jadwal_dokter', 'id')
                    ->where('is_active', true),
            ],
            'tanggal_kunjungan' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'jam_estimasi' => [
                'required',
                'string',
                'max:30',
            ],
            'jenis_penjamin' => [
                'required',
                Rule::in(['Umum', 'BPJS', 'Asuransi']),
            ],
            'nomor_peserta' => [
                'nullable',
                'string',
                'max:50',
                'required_unless:jenis_penjamin,Umum',
            ],
            'keluhan_awal' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ], [
            'poli_id.required' => 'Silakan pilih poli tujuan.',
            'jadwal_dokter_id.required' => 'Silakan pilih jadwal dokter.',
            'tanggal_kunjungan.after_or_equal' =>
                'Tanggal kunjungan tidak boleh sebelum hari ini.',
            'jam_estimasi.required' => 'Silakan pilih perkiraan jam kedatangan.',
            'jenis_penjamin.in' => 'Jenis penjamin tidak valid.',
            'nomor_peserta.required_unless' =>
                'Nomor peserta wajib diisi untuk BPJS atau Asuransi.',
        ]);

        // Proses penyimpanan
        
        $tanggal = Carbon::parse($validated['tanggal_kunjungan']);

        $hariIndonesia = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        $hariKunjungan = $hariIndonesia[$tanggal->format('l')];

        try {
            $pendaftaran = DB::transaction(function () use (
                $validated,
                $pasien,
                $tanggal,
                $hariKunjungan
            ) {
                $jadwal = JadwalDokter::with('dokter')
                    ->whereKey($validated['jadwal_dokter_id'])
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->first();

                if (
                    !$jadwal ||
                    !$jadwal->dokter ||
                    !$jadwal->dokter->is_active ||
                    (int) $jadwal->dokter->poli_id !== (int) $validated['poli_id']
                ) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'jadwal_dokter_id' => 'Jadwal dokter tidak sesuai dengan poli yang dipilih.',
                    ]);
                }

                $poliAktif = Poli::whereKey($validated['poli_id'])
                    ->where('is_active', true)
                    ->exists();

                if (!$poliAktif) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'poli_id' => 'Poli yang dipilih sudah tidak aktif.',
                    ]);
                }

                if (mb_strtolower($jadwal->hari) !== mb_strtolower($hariKunjungan)) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'tanggal_kunjungan' => 'Tanggal yang dipilih tidak sesuai dengan hari praktik dokter.',
                    ]);
                }

                $jumlahTerdaftar = \App\Models\Pendaftaran::query()
                    ->where('jadwal_dokter_id', $jadwal->id)
                    ->whereDate('tanggal_kunjungan', $tanggal->toDateString())
                    ->whereIn('status', ['menunggu', 'terverifikasi', 'terdaftar'])
                    ->count();

                if ($jumlahTerdaftar >= $jadwal->kuota) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'jadwal_dokter_id' => 'Kuota jadwal dokter pada tanggal tersebut sudah penuh.',
                    ]);
                }

                $nomorAntrean = \App\Models\Pendaftaran::query()
                    ->where('poli_id', $validated['poli_id'])
                    ->whereDate('tanggal_kunjungan', $tanggal->toDateString())
                    ->lockForUpdate()
                    ->max('nomor_antrean');

                $nomorAntrean = ($nomorAntrean ?? 0) + 1;

                return \App\Models\Pendaftaran::create([
                    'kode_pendaftaran' => 'RJ-' . now()->format('Ymd') . '-' . Str::upper(Str::random(8)),
                    'pasien_id' => $pasien->id,
                    'poli_id' => $validated['poli_id'],
                    'jadwal_dokter_id' => $jadwal->id,
                    'tanggal_kunjungan' => $tanggal->toDateString(),
                    'jam_estimasi' => $validated['jam_estimasi'],
                    'nomor_antrean' => $nomorAntrean,
                    'jenis_penjamin' => $validated['jenis_penjamin'],
                    'nomor_peserta' => $validated['jenis_penjamin'] === 'Umum'
                        ? null
                        : $validated['nomor_peserta'],
                    'status_verifikasi' => 'menunggu_konfirmasi',
                    'keluhan_awal' => $validated['keluhan_awal'] ?? null,
                    'status' => 'menunggu_konfirmasi',
                    'created_by' => auth()->id(),
                ]);
            }, 3);
        } catch (\Illuminate\Database\QueryException $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'pendaftaran' => 'Pendaftaran belum dapat disimpan. Silakan coba kembali.',
                ]);
        }

        return redirect()
            ->route('pasien.pendaftaran.show', $pendaftaran)
            ->with('success', 'Pendaftaran berhasil dikirim. Menunggu konfirmasi ketersediaan dokter oleh admin. Nomor antrean sementara: #' . $pendaftaran->nomor_antrean);

    }

    /**
     * Menampilkan detail satu pendaftaran milik pasien yang login.
     */
    public function show(Pendaftaran $pendaftaran): View
    {
        $pasien = auth()->user()->pasien;

        if (!$pasien || $pendaftaran->pasien_id !== $pasien->id) {
            abort(403, 'Kamu tidak memiliki akses ke pendaftaran ini.');
        }

        $pendaftaran->load(['poli', 'jadwalDokter']);

        return view('pendaftaran.show', compact('pendaftaran'));
    }
}