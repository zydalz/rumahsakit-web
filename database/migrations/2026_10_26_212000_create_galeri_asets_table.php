<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeri_asets', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 150);
            $table->string('kategori', 50)->default('galeri'); // banner, fasilitas, kegiatan, layanan
            $table->string('file_path');
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_asets');
    }
};
