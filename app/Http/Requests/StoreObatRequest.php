<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreObatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_obat' => ['required', 'string', 'max:30', 'unique:obats,kode_obat'],
            'nama_obat' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:30'],
            'harga' => ['required', 'numeric', 'min:0'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}