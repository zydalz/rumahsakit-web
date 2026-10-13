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
            'keluhan'        => ['required', 'string'],
            'tekanan_darah'  => ['nullable', 'string', 'max:20'],
            'suhu'           => ['nullable', 'numeric', 'between:30,45'],
            'berat_badan'    => ['nullable', 'numeric', 'between:1,300'],
            'tinggi_badan'   => ['nullable', 'numeric', 'between:30,250'],
            'diagnosis'      => ['required', 'string'],
            'catatan'        => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'pendaftaran_id.required' => 'Data pendaftaran pasien wajib dipilih.',
            'pendaftaran_id.exists'   => 'Data pendaftaran pasien tidak ditemukan.',
            'keluhan.required'        => 'Keluhan utama pasien wajib diisi.',
            'diagnosis.required'      => 'Diagnosis dokter wajib diisi.',
            'suhu.between'            => 'Nilai suhu tubuh tidak valid (30 - 45 °C).',
            'berat_badan.between'     => 'Nilai berat badan tidak valid.',
            'tinggi_badan.between'    => 'Nilai tinggi badan tidak valid.',
        ];
    }
    
}
