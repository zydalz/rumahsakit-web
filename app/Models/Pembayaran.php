<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'tagihan_id',
        'kasir_id',
        'tanggal_bayar',
        'metode',
        'jumlah_bayar',
        'status',
        'referensi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bayar' => 'datetime',
            'jumlah_bayar' => 'decimal:2',
        ];
    }

    public function tagihan()
    {
        return $this->belongsTo(Tagihan::class, 'tagihan_id');
    }

    public function kasir()
    {
        return $this->belongsTo(User::class, 'kasir_id');
    }
}