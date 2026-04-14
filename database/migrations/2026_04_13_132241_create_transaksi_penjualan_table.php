<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi_penjualan', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique();
            $table->dateTime('tanggal_transaksi');
            $table->decimal('total_harga', 15, 2);
            $table->decimal('total_modal', 15, 2)->default(0);
            $table->decimal('total_keuntungan', 15, 2)->default(0);
            $table->enum('metode_pembayaran', ['tunai', 'debit', 'qris', 'transfer'])->default('tunai');
            $table->decimal('uang_bayar', 15, 2)->nullable();
            $table->decimal('uang_kembali', 15, 2)->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_penjualan');
    }
};
