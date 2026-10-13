<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemeriksaanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pendaftaran_id' => ['required', 'exists:pendaftaran,id', 'unique:pemeriksaan,pendaftaran_id'],
            'dokter_id' => ['required', 'exists:dokter,id'],
            'keluhan' => ['required', 'string'],
            'tekanan_darah' => ['nullable', 'string', 'max:20'],
            'suhu' => ['nullable', 'numeric', 'between:30.0,45.0'],
            'berat_badan' => ['nullable', 'numeric', 'min:0'],
            'tinggi_badan' => ['nullable', 'numeric', 'min:0'],
            'diagnosis' => ['required', 'string'],
            'catatan' => ['nullable', 'string'],
        ];
    }
}
