<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalDokter extends Model
{
    protected $table = 'jadwal_dokter';
    protected $fillable = ['dokter_id', 'hari', 'jam_mulai', 'jam_selesai', 'kuota', 'is_active'];

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }

    public function pendaftarans(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pendaftaran::class, 'jadwal_dokter_id');
    }
}
