<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemeriksaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();

        return $user
            && $user->role === 'dokter'
            && $user->is_active
            && $user->dokter
            && $user->dokter->is_active;
    }

    public function rules(): array
    {
        return [
            'pendaftaran_id' => ['required', 'exists:pendaftaran,id'],
            'keluhan' => ['required', 'string'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'suhu' => ['nullable', 'numeric', 'between:30,45'],
            'berat_badan' => ['nullable', 'numeric', 'between:1,300'],
            'tinggi_badan' => ['nullable', 'numeric', 'between:30,250'],
            'diagnosis' => ['required', 'string'],
            'catatan' => ['nullable', 'string'],

            'obat' => ['nullable', 'array'],
            'obat.*.obat_id' => [
                'required',
                'integer',
                'distinct',
                'exists:obats,id',
            ],
            'obat.*.jumlah' => [
                'required',
                'integer',
                'min:1',
                'max:1000',
            ],
            'obat.*.dosis' => [
                'required',
                'string',
                'max:255',
            ],
            'obat.*.instruksi_khusus' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'pendaftaran_id.required' => 'Pendaftaran pasien wajib dipilih.',
            'pendaftaran_id.exists' => 'Pendaftaran pasien tidak ditemukan.',
            'keluhan.required' => 'Keluhan utama pasien wajib diisi.',
            'diagnosis.required' => 'Diagnosis dokter wajib diisi.',
            'obat.*.obat_id.required' => 'Pilih obat yang akan diresepkan.',
            'obat.*.obat_id.exists' => 'Obat yang dipilih tidak ditemukan.',
            'obat.*.obat_id.distinct' => 'Obat yang sama tidak boleh dipilih dua kali.',
            'obat.*.jumlah.required' => 'Jumlah obat wajib diisi.',
            'obat.*.jumlah.min' => 'Jumlah obat minimal 1.',
            'obat.*.dosis.required' => 'Dosis obat wajib diisi.',
        ];
    }
}