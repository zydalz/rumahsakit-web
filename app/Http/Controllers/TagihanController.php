<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TagihanController extends Controller
{
    public function index()
    {
        $tagihans = Tagihan::with([
            'pendaftaran.pasien',
            'pembayaran',
        ])
            ->latest()
            ->get();

        return view('tagihan.index', compact('tagihans'));
    }

    public function create()
    {
        $pendaftarans = Pendaftaran::with('pasien')
            ->whereDoesntHave('tagihan')
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        return view('tagihan.create', compact('pendaftarans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_tagihan' => [
                'required',
                'string',
                'max:30',
                'unique:tagihans,nomor_tagihan',
            ],
            'pendaftaran_id' => [
                'required',
                'integer',
                Rule::exists('pendaftaran', 'id'),
                Rule::unique('tagihans', 'pendaftaran_id'),
            ],
            'total_tagihan' => [
                'required',
                'numeric',
                'min:0.01',
                'max:9999999999.99',
            ],
        ]);

        Tagihan::create([
            'nomor_tagihan' => $validated['nomor_tagihan'],
            'pendaftaran_id' => $validated['pendaftaran_id'],
            'total_tagihan' => $validated['total_tagihan'],
            'status' => 'belum_lunas',
        ]);

        return redirect()
            ->route('tagihan.index')
            ->with('success', 'Tagihan berhasil dibuat.');
    }

    public function show(string $id)
    {
        $tagihan = Tagihan::with([
            'pendaftaran.pasien',
            'detailTagihan',
            'pembayaran',
        ])->findOrFail($id);

        return view('tagihan.show', compact('tagihan'));
    }

    public function edit(string $id)
    {
        abort(404);
    }

    public function update(Request $request, string $id)
    {
        abort(404);
    }

    public function destroy(string $id)
    {
        abort(404);
    }
}