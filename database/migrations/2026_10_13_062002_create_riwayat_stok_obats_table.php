<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_stok_obat', function (Blueprint $table) {
    $table->id();
    $table->foreignId('obat_id')->constrained('obats')->onDelete('cascade');
    $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
    $table->string('jenis_perubahan', 20); // misal: 'masuk', 'keluar', 'penyesuaian'
    $table->unsignedInteger('jumlah');
    $table->unsignedInteger('stok_sebelum');
    $table->unsignedInteger('stok_sesudah');
    $table->string('keterangan', 255)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_stok_obats');
    }
};
