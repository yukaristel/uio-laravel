<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movement';

    public $timestamps = false;

    protected $fillable = [
        'bahan_id',
        'jenis_pergerakan',
        'jumlah',
        'satuan',
        'harga_per_satuan',
        'total_nilai',
        'stok_sebelum',
        'stok_sesudah',
        'referensi_type',
        'referensi_id',
        'keterangan',
        'user_id',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'harga_per_satuan' => 'decimal:2',
        'total_nilai' => 'decimal:2',
        'stok_sebelum' => 'decimal:2',
        'stok_sesudah' => 'decimal:2',
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
