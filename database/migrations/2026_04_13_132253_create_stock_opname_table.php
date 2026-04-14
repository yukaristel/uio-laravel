<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->string('no_opname', 50)->unique();
            $table->date('tanggal_opname');
            $table->foreignId('bahan_id')->constrained('bahan_baku');
            $table->decimal('stok_sistem', 10, 2);
            $table->decimal('stok_fisik', 10, 2);
            $table->decimal('selisih', 10, 2);
            $table->string('satuan', 20);
            $table->decimal('harga_per_satuan', 15, 2);
            $table->decimal('nilai_selisih', 15, 2);
            $table->enum('jenis_selisih', ['hilang', 'rusak', 'expired', 'tumpah', 'salah_hitung', 'lainnya'])->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['draft', 'approved'])->default('draft');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('bahan_id');
            $table->index('user_id');
            $table->index('approved_by');
            $table->index('tanggal_opname', 'idx_tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_opname');
    }
};
