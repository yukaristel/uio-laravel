<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_bahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan_baku');
            $table->decimal('jumlah_beli', 10, 2);
            $table->decimal('harga_beli_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2);
            $table->string('supplier', 100)->nullable();
            $table->date('tanggal_beli');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index('bahan_id');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan');
    }
};
