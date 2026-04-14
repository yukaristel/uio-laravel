<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResepMenu extends Model
{
    use HasFactory;

    protected $table = 'resep_menu';

    protected $fillable = [
        'menu_id',
        'bahan_id',
        'jumlah_bahan',
        'satuan',
        'biaya_bahan',
    ];

    protected $casts = [
        'jumlah_bahan' => 'decimal:2',
        'biaya_bahan' => 'decimal:2',
    ];

    public function menu()
    {
        return $this->belongsTo(MenuMakanan::class, 'menu_id');
    }

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }
}
