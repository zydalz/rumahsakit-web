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
       Schema::create('obats', function (Blueprint $table) {
    $table->id();
    $table->string('kode_obat', 30)->unique();
    $table->string('nama_obat', 150);
    $table->string('satuan', 30);
    $table->decimal('harga', 12, 2);
    $table->unsignedInteger('stok');
    $table->unsignedInteger('stok_minimum');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obats');
    }
};
