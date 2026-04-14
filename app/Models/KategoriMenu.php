<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriMenu extends Model
{
    use HasFactory;

    protected $table = 'kategori_menu';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    public function menus()
    {
        return $this->hasMany(MenuMakanan::class, 'kategori_id');
    }
}
