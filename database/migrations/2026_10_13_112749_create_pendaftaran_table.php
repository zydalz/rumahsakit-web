<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id();

            $table->string('kode_pendaftaran', 30)->unique();

            $table->foreignId('pasien_id')
                ->constrained('pasien')
                ->restrictOnDelete();

            $table->foreignId('poli_id')
                ->constrained('poli')
                ->restrictOnDelete();

            $table->foreignId('jadwal_dokter_id')
                ->constrained('jadwal_dokter')
                ->restrictOnDelete();

            $table->date('tanggal_kunjungan');
            $table->unsignedInteger('nomor_antrean');

            $table->string('jenis_penjamin', 20);
            $table->string('nomor_peserta', 50)->nullable();
            $table->string('status_verifikasi', 20);
            $table->text('keluhan_awal')->nullable();
            $table->string('status', 20);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['poli_id', 'tanggal_kunjungan', 'nomor_antrean'],
                'pendaftaran_antrean_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};