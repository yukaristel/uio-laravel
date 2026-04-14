<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuMakanan extends Model
{
    use HasFactory;

    protected $table = 'menu_makanan';

    protected $fillable = [
        'kode_menu',
        'nama_menu',
        'kategori_id',
        'harga_modal',
        'harga_jual',
        'margin_keuntungan',
        'status',
        'foto_menu',
    ];

    protected $casts = [
        'harga_modal' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'margin_keuntungan' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }

    public function reseps()
    {
        return $this->hasMany(ResepMenu::class, 'menu_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id');
    }
}
