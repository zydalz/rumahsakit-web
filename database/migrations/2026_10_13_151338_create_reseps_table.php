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
            // Ubah jadi unsignedBigInteger biasa agar aman dari beda nama tabel
            $table->unsignedBigInteger('pendaftaran_id'); 
            $table->string('kode_resep')->unique();
            $table->text('catatan_dokter')->nullable();
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->timestamps();
        });
    }
};
