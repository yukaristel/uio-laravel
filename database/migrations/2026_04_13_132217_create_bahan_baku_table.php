<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id();
            $table->string('kode_bahan', 20)->unique();
            $table->string('nama_bahan', 100);
            $table->enum('satuan', ['kg', 'gram', 'liter', 'ml', 'pcs', 'sachet']);
            $table->decimal('stok_tersedia', 10, 2)->default(0);
            $table->decimal('stok_minimum', 10, 2)->default(0);
            $table->decimal('harga_beli_per_satuan', 15, 2);
            $table->timestamps();

            $table->index('kode_bahan');
            $table->index('nama_bahan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
