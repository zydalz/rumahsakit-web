<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailResep extends Model
{
    use HasFactory;

    protected $table = 'detail_reseps';

    protected $fillable = [
        'resep_id',
        'obat_id',
        'jumlah',
        'dosis',
        'subtotal',
    ];

    // Relasi balik ke Resep
    public function resep()
    {
        return $this->belongsTo(Resep::class, 'resep_id', 'id');
    }
}