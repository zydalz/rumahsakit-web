<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePoliRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_poli' => ['required', 'string', 'max:100', 'unique:poli,nama_poli'],
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