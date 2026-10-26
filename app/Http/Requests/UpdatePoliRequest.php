<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePoliRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $poli = $this->route('poli');
        $poliId = is_object($poli) ? $poli->id : $poli;

        return [
            'nama_poli' => [
                'required',
                'string',
                'max:100',
                Rule::unique('poli', 'nama_poli')->ignore($poliId),
            ],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_poli.required' => 'Nama poliklinik wajib diisi.',
            'nama_poli.unique' => 'Nama poliklinik sudah terdaftar.',
            'nama_poli.max' => 'Nama poliklinik maksimal 100 karakter.',
        ];
    }
}
