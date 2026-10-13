<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pemeriksaan extends Model
{
    protected $table = 'pemeriksaan';
    protected $fillable = [
        'pendaftaran_id', 'dokter_id', 'keluhan', 
        'tekanan_darah', 'suhu', 'berat_badan', 
        'tinggi_badan', 'diagnosis', 'catatan'
    ];

    public function pendaftaran(): BelongsTo
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'dokter_id');
    }
}
