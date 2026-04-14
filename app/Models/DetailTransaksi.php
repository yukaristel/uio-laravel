<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksi';

    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'menu_id',
        'jumlah',
        'harga_satuan',
        'harga_modal_satuan',
        'subtotal',
        'subtotal_modal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'harga_modal_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'subtotal_modal' => 'decimal:2',
    ];

    public function transaksi()
    {
        return $this->belongsTo(TransaksiPenjualan::class, 'transaksi_id');
    }

    public function menu()
    {
        return $this->belongsTo(MenuMakanan::class, 'menu_id');
    }
}
