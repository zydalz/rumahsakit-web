<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Pendaftaran;

class Tagihan extends Model
{
    use HasFactory;

    protected $table = 'tagihans';

    protected $fillable = [
        'nomor_tagihan',
        'pendaftaran_id',
        'total_tagihan',
        'status',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(\App\Models\Pendaftaran::class, 'pendaftaran_id');
    }

    public function detailTagihan()
    {
        return $this->hasMany(DetailTagihan::class, 'tagihan_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'tagihan_id');
    }

    // Menghitung sisa tagihan berdasarkan pembayaran yang berhasil
    public function getSisaTagihanAttribute()
    {
        $totalBayar = $this->pembayaran()->where('status', 'berhasil')->sum('jumlah_bayar');
        return max(0, $this->total_tagihan - $totalBayar);
    }
}