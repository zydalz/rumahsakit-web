<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    protected $table = 'pasien';

    protected $fillable = [
        'user_id',
        'nama',
        'nomor_rm',
        'nik',
        'tanggal_lahir',
        'jenis_kelamin',
        'golongan_darah',
        'riwayat_alergi',
        'alamat',
        'no_telepon',
        'kontak_darurat',
        'hubungan_darurat',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    public function pendaftarans(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}