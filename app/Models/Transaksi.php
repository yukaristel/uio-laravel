<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    public $timestamps = false;

    protected $fillable = [
        'tgl_transaksi',
        'rekening_debet',
        'rekening_kredit',
        'keterangan_transaksi',
        'jumlah',
        'id_user',
    ];

    protected $casts = [
        'tgl_transaksi' => 'date',
        'jumlah' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function rekeningDebet()
    {
        return $this->belongsTo(ChartOfAccount::class, 'rekening_debet', 'kode_akun');
    }

    public function rekeningKredit()
    {
        return $this->belongsTo(ChartOfAccount::class, 'rekening_kredit', 'kode_akun');
    }
}
