<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GaleriAset extends Model
{
    protected $table = 'galeri_asets';

    protected $fillable = [
        'judul',
        'kategori',
        'file_path',
        'deskripsi',
        'urutan',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    /**
     * URL gambar lengkap (baik yang diunggah ke storage/uploads maupun tautan eksternal).
     */
    public function getGambarUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        if (str_starts_with($this->file_path, 'uploads/')) {
            return asset($this->file_path);
        }

        return asset('uploads/galeri/' . $this->file_path);
    }
}
