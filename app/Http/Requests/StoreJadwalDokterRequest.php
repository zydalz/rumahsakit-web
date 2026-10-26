<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJadwalDokterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->jam_mulai && strlen($this->jam_mulai) > 5) {
            $this->merge(['jam_mulai' => substr($this->jam_mulai, 0, 5)]);
        }
        if ($this->jam_selesai && strlen($this->jam_selesai) > 5) {
            $this->merge(['jam_selesai' => substr($this->jam_selesai, 0, 5)]);
        }
    }

    public function rules(): array
    {
        return [
            'dokter_id' => ['required', 'exists:dokter,id'],
            'hari' => ['required', 'string', 'max:10'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'kuota' => ['required', 'integer', 'min:1'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}