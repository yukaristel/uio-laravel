<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('lev1');
            $table->integer('lev2');
            $table->integer('lev3');
            $table->integer('lev4');
            $table->string('kode_akun', 20)->unique();
            $table->string('nama_akun', 200);
            $table->enum('jenis_mutasi', ['Debet', 'Kredit']);
            $table->tinyInteger('posisi')->comment('1=Neraca, 2=Laba Rugi');
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();

            $table->index('kode_akun', 'idx_kode');
            $table->index(['lev1', 'lev2', 'lev3', 'lev4'], 'idx_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};
