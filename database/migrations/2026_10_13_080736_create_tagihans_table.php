
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('tagihans')) {
            return;
        }

        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tagihan', 30)->unique();

            $table->foreignId('pendaftaran_id')
                ->unique()
                ->constrained('pendaftaran')
                ->cascadeOnDelete();

            $table->decimal('total_tagihan', 12, 2);
            $table->string('status', 20)->default('belum_lunas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
