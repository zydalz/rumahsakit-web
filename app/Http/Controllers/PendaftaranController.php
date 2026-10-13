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

        return view('pendaftaran.create');
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
            'jenis_penjamin.in' => 'Jenis penjamin tidak valid.',
            'nomor_peserta.required_unless' =>
                'Nomor peserta wajib diisi untuk BPJS atau Asuransi.',
        ]);

        // Proses penyimpanan dilanjutkan pada langkah berikutnya.
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