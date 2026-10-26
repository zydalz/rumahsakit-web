<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PasienController extends Controller
{
    
public function index(Request $request)
{
    $search = $request->query('search');

    $pasien = Pasien::with('user')
        ->when($search, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('nomor_rm', 'like', "%{$search}%")
                    ->orWhere('nik', 'like', "%{$search}%");
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('pasien.index', compact('pasien'));
}

    public function show(Pasien $pasien)
    {
        $pasien->load('user');

        return view('pasien.show', compact('pasien'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nomor_rm' => ['required', 'string', 'max:20', 'unique:pasien,nomor_rm'],
            'nik' => ['nullable', 'digits:16', 'unique:pasien,nik'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'golongan_darah' => ['nullable', 'string', 'max:5'],
            'riwayat_alergi' => ['nullable', 'string', 'max:1000'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'kontak_darurat' => ['nullable', 'string', 'max:25'],
            'hubungan_darurat' => ['nullable', 'string', 'max:50'],
        ]);

        Pasien::create($validated);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function edit(Pasien $pasien)
    {
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nomor_rm' => [
                'required',
                'string',
                'max:20',
                Rule::unique('pasien', 'nomor_rm')->ignore($pasien->id),
            ],
            'nik' => [
                'nullable',
                'digits:16',
                Rule::unique('pasien', 'nik')->ignore($pasien->id),
            ],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', Rule::in(['L', 'P'])],
            'golongan_darah' => ['nullable', 'string', 'max:5'],
            'riwayat_alergi' => ['nullable', 'string', 'max:1000'],
            'alamat' => ['nullable', 'string'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'kontak_darurat' => ['nullable', 'string', 'max:25'],
            'hubungan_darurat' => ['nullable', 'string', 'max:50'],
        ]);

        $pasien->update($validated);

        return redirect()->route('admin.pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }
}