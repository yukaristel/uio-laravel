<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saldo', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('kode_akun', 50);
            $table->bigInteger('tahun');
            $table->bigInteger('bulan');
            $table->bigInteger('debet')->nullable();
            $table->bigInteger('kredit')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saldo');
    }
};
