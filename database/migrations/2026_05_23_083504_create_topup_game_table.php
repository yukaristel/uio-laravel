<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topup_game', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaksi', 30)->unique();
            $table->string('nama_game', 50);
            $table->string('nama_item', 100)->comment('contoh: Diamond, UC, dll');
            $table->decimal('jumlah_item', 10, 2)->comment('jumlah diamond/UC dll');
            $table->decimal('harga_beli', 15, 2)->comment('HPP / modal');
            $table->decimal('harga_jual', 15, 2)->comment('harga yang diterima');
            $table->decimal('keuntungan', 15, 2)->virtualAs('harga_jual - harga_beli');
            $table->string('rekening_beli', 20)->comment('kas yang dipakai beli');
            $table->string('rekening_bayar', 20)->comment('kas yang diterima');
            $table->string('id_pelanggan', 100)->nullable()->comment('ID game pelanggan');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users');
            $table->timestamp('created_at')->useCurrent();

            $table->index('nama_game');
            $table->index('created_at');
            $table->foreign('rekening_beli')->references('kode_akun')->on('chart_of_accounts');
            $table->foreign('rekening_bayar')->references('kode_akun')->on('chart_of_accounts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topup_game');
    }
};
