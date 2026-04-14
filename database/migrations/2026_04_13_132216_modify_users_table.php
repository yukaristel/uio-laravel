<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->dropColumn('name');
            $table->string('nama_lengkap')->after('username');
            $table->enum('role', ['admin', 'karyawan', 'kasir'])->default('karyawan')->after('nama_lengkap');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'nama_lengkap', 'role']);
            $table->string('name')->after('id');
        });
    }
};
