<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->string('golongan_darah', 5)->nullable()->after('jenis_kelamin');
            $table->text('riwayat_alergi')->nullable()->after('golongan_darah');
            $table->string('kontak_darurat', 25)->nullable()->after('no_telepon');
            $table->string('hubungan_darurat', 50)->nullable()->after('kontak_darurat');
        });
    }

    public function down(): void
    {
        Schema::table('pasien', function (Blueprint $table) {
            $table->dropColumn(['golongan_darah', 'riwayat_alergi', 'kontak_darurat', 'hubungan_darurat']);
        });
    }
};
