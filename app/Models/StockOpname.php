<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    use HasFactory;

    protected $table = 'stock_opname';

    public $timestamps = false;

    protected $fillable = [
        'no_opname',
        'tanggal_opname',
        'bahan_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
        'satuan',
        'harga_per_satuan',
        'nilai_selisih',
        'jenis_selisih',
        'keterangan',
        'status',
        'user_id',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tanggal_opname' => 'date',
        'stok_sistem' => 'decimal:2',
        'stok_fisik' => 'decimal:2',
        'selisih' => 'decimal:2',
        'harga_per_satuan' => 'decimal:2',
        'nilai_selisih' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function bahanBaku()
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
