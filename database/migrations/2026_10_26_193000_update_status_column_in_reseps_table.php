<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ubah kolom status menjadi VARCHAR(50) agar mendukung status alur farmasi & kasir yang lengkap
        DB::statement("ALTER TABLE reseps MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'menunggu_pembayaran'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE reseps MODIFY COLUMN status ENUM('menunggu', 'diproses', 'selesai') NOT NULL DEFAULT 'menunggu'");
    }
};
