<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatStokObat extends Model
{
    use HasFactory;

    protected $table = 'riwayat_stok_obat';

    protected $fillable = [
        'obat_id',
        'user_id',
        'jenis_perubahan',
        'jumlah',
        'stok_sebelum',
        'stok_sesudah',
        'keterangan',
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}