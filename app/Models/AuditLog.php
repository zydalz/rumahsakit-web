<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class AuditLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'modul',
        'aksi',
        'deskripsi',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke pengguna yang melakukan aksi
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat log aktivitas secara aman dan otomatis
     */
    public static function catat(string $modul, string $aksi, string $deskripsi, ?User $user = null): ?self
    {
        try {
            $currentUser = $user ?? Auth::user();
            $request = request();

            return self::create([
                'user_id' => $currentUser?->id,
                'user_name' => $currentUser?->name ?? 'Tamu / Sistem',
                'role' => $currentUser?->role ?? 'tamu',
                'modul' => $modul,
                'aksi' => $aksi,
                'deskripsi' => $deskripsi,
                'ip_address' => $request?->ip(),
                'user_agent' => $request?->userAgent() ? substr($request->userAgent(), 0, 500) : null,
                'created_at' => now(),
            ]);
        } catch (Throwable $e) {
            // Pencatatan audit log tidak boleh menggagalkan transaksi utama jika terjadi kendala
            Log::warning('Gagal menyimpan audit log: ' . $e->getMessage(), [
                'modul' => $modul,
                'aksi' => $aksi,
            ]);

            return null;
        }
    }
}
