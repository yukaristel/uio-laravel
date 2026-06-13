<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuHargaGrosir extends Model
{
    use HasFactory;

    protected $table = 'menu_harga_grosir';

    protected $fillable = [
        'menu_id',
        'min_qty',
        'harga_total',
    ];

    protected $casts = [
        'min_qty'     => 'integer',
        'harga_total' => 'decimal:2',
    ];

    public function menu()
    {
        return $this->belongsTo(MenuMakanan::class, 'menu_id');
    }
}
