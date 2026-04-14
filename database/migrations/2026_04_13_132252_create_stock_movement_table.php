<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movement', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bahan_id')->constrained('bahan_baku');
            $table->enum('jenis_pergerakan', ['masuk', 'keluar', 'opname', 'rusak', 'expired', 'hilang', 'tumpah']);
            $table->decimal('jumlah', 10, 2);
            $table->string('satuan', 20);
            $table->decimal('harga_per_satuan', 15, 2);
            $table->decimal('total_nilai', 15, 2);
            $table->decimal('stok_sebelum', 10, 2);
            $table->decimal('stok_sesudah', 10, 2);
            $table->enum('referensi_type', ['pembelian', 'penjualan', 'opname', 'manual']);
            $table->integer('referensi_id')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index(['bahan_id', 'created_at'], 'idx_bahan_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movement');
    }
};
