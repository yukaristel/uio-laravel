<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaksi_id')->constrained('transaksi_penjualan')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menu_makanan');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('harga_modal_satuan', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2);
            $table->decimal('subtotal_modal', 15, 2)->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('transaksi_id');
            $table->index('menu_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_transaksi');
    }
};
