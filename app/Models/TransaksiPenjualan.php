<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPenjualan extends Model
{
    use HasFactory;

    protected $table = 'transaksi_penjualan';

    public $timestamps = false;

    protected $fillable = [
        'no_transaksi',
        'tanggal_transaksi',
        'total_harga',
        'total_modal',
        'total_keuntungan',
        'metode_pembayaran',
        'uang_bayar',
        'uang_kembali',
        'user_id',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
        'total_harga' => 'decimal:2',
        'total_modal' => 'decimal:2',
        'total_keuntungan' => 'decimal:2',
        'uang_bayar' => 'decimal:2',
        'uang_kembali' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
