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
       Schema::create('pembayarans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tagihan_id')->constrained('tagihans')->onDelete('cascade');
    $table->foreignId('kasir_id')->constrained('users')->onDelete('cascade');
    $table->dateTime('tanggal_bayar');
    $table->string('metode', 20); // 'tunai', 'transfer', 'asuransi'
    $table->decimal('jumlah_bayar', 12, 2);
    $table->string('status', 20)->default('berhasil'); // berhasil, batal
    $table->string('referensi', 100)->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
