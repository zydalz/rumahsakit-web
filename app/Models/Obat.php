<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obats';

    protected $fillable = [
        'kode_obat',
        'nama_obat',
        'satuan',
        'harga',
        'stok',
        'stok_minimum',
        'is_active',
    ];

    public function riwayatStok()
    {
        return $this->hasMany(RiwayatStokObat::class, 'obat_id');
    }

}