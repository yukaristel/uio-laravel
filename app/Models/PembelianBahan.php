<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    use HasFactory;

    protected $table = 'pembelian_bahan';

    public $timestamps = false;

    protected $fillable = [
        'bahan_id',
        'jumlah_beli',
        'harga_beli_satuan',
        'total_harga',
        'supplier',
        'tanggal_beli',
        'user_id',
    ];

    protected $casts = [
        'jumlah_beli' => 'decimal:2',
        'harga_beli_satuan' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tanggal_beli' => 'date',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
