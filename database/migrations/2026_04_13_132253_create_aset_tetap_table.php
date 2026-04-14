<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aset_tetap', function (Blueprint $table) {
            $table->id('id_aset');
            $table->string('nama_barang', 200);
            $table->date('tgl_beli');
            $table->integer('unit')->default(1);
            $table->decimal('harsat', 15, 2);
            $table->integer('umur_ekonomis')->nullable()->comment('Umur ekonomis dalam bulan');
            $table->decimal('akumulasi_penyusutan', 15, 2)->default(0);
            $table->enum('jenis', ['Peralatan Dapur', 'Furniture', 'Elektronik', 'Lainnya'])->default('Lainnya');
            $table->enum('metode_beli', ['tunai', 'transfer', 'kredit'])->default('tunai');
            $table->enum('status', ['Baik', 'Rusak', 'Dijual', 'Hapus', 'Maintenance'])->default('Baik');
            $table->date('tgl_validasi')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('jenis', 'idx_jenis');
            $table->index('status', 'idx_status');
            $table->index('tgl_beli', 'idx_tgl_beli');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aset_tetap');
    }
};
