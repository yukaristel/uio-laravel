<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_makanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_menu', 20)->unique();
            $table->string('nama_menu', 100);
            $table->foreignId('kategori_id')->constrained('kategori_menu');
            $table->decimal('harga_modal', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('margin_keuntungan', 10, 2)->default(0);
            $table->enum('status', ['tersedia', 'habis', 'tidak_tersedia'])->default('tersedia');
            $table->string('foto_menu', 255)->nullable();
            $table->timestamps();

            $table->index('kategori_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_makanan');
    }
};
