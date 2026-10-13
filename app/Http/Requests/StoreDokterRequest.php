<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDokterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id', 'unique:dokter,user_id'],
            'poli_id' => ['required', 'exists:poli,id'],
            'nomor_sip' => ['nullable', 'string', 'max:50'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'is_active' => ['boolean'],
        ];
    }
}
