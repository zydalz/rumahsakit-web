<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTagihan extends Model
{
    use HasFactory;

    protected $table = 'detail_tagihans';

    protected $fillable = [
        'tagihan_id',
        'jenis_item',
        'deskripsi',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    
public function obat()
{
    return $this->belongsTo(Obat::class, 'obat_id');
}

}