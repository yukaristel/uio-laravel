<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resep_menu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu_makanan')->onDelete('cascade');
            $table->foreignId('bahan_id')->constrained('bahan_baku');
            $table->decimal('jumlah_bahan', 10, 2);
            $table->enum('satuan', ['kg', 'gram', 'liter', 'ml', 'pcs', 'sachet']);
            $table->decimal('biaya_bahan', 15, 2)->default(0);
            $table->timestamps();

            $table->index('menu_id');
            $table->index('bahan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resep_menu');
    }
};
