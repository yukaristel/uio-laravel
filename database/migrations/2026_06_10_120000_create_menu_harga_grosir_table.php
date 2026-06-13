<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_harga_grosir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained('menu_makanan')->onDelete('cascade');
            $table->unsignedInteger('min_qty');
            $table->decimal('harga_per_unit', 15, 2);
            $table->timestamps();

            $table->index('menu_id');
            $table->unique(['menu_id', 'min_qty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_harga_grosir');
    }
};
