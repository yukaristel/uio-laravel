<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopupGame extends Model
{
    use HasFactory;

    protected $table = 'topup_game';

    public $timestamps = false;

    protected $fillable = [
        'no_transaksi',
        'nama_game',
        'nama_item',
        'jumlah_item',
        'harga_beli',
        'harga_jual',
        'rekening_beli',
        'rekening_bayar',
        'id_pelanggan',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'jumlah_item' => 'decimal:2',
        'harga_beli'  => 'decimal:2',
        'harga_jual'  => 'decimal:2',
        'keuntungan'  => 'decimal:2',
        'created_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rekeningBeli()
    {
        return $this->belongsTo(ChartOfAccount::class, 'rekening_beli', 'kode_akun');
    }

    public function rekeningBayar()
    {
        return $this->belongsTo(ChartOfAccount::class, 'rekening_bayar', 'kode_akun');
    }
}
