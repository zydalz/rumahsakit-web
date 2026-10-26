<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    protected $table = 'poli';
    protected $fillable = ['nama_poli', 'deskripsi', 'is_active'];

    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class, 'poli_id');
    }

    public function dokters(): HasMany
    {
        return $this->hasMany(Dokter::class, 'poli_id');
    }
}
