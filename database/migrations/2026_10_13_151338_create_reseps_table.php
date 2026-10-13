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
    Schema::create('reseps', function (Blueprint $table) {
        $table->id();
        $table->foreignId('pendaftaran_id')->constrained('pendaftarans')->onDelete('cascade');
        $table->string('kode_resep')->unique(); // Contoh: RSP-20261013-001
        $table->text('catatan_dokter')->nullable();
        $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
        $table->timestamps();
    });
}
};
