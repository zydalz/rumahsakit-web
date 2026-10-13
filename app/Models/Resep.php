<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'reseps';

    protected $fillable = [
        'pendaftaran_id',
        'kode_resep',
        'catatan_dokter',
        'status',
    ];

    // Relasi ke Pendaftaran (karena foreign key-nya tanpa constraint ketat di DB)
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'id');
    }

    // Relasi ke Detail Resep (1 Resep punya banyak obat/detail)
    public function detailReseps()
    {
        return $this->hasMany(DetailResep::class, 'resep_id', 'id');
    }
}