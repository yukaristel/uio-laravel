<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    use HasFactory;

    protected $table = 'bahan_baku';

    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'satuan',
        'stok_tersedia',
        'stok_minimum',
        'harga_beli_per_satuan',
    ];

    protected $casts = [
        'stok_tersedia' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
        'harga_beli_per_satuan' => 'decimal:2',
    ];

    public function pembelians()
    {
        return $this->hasMany(PembelianBahan::class, 'bahan_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'bahan_id');
    }

    public function reseps()
    {
        return $this->hasMany(ResepMenu::class, 'bahan_id');
    }

    public function stockOpnames()
    {
        return $this->hasMany(StockOpname::class, 'bahan_id');
    }

    public function isStokMenuipis(): bool
    {
        return $this->stok_tersedia <= $this->stok_minimum;
    }
}
