<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChartOfAccount extends Model
{
    use HasFactory;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'lev1',
        'lev2',
        'lev3',
        'lev4',
        'kode_akun',
        'nama_akun',
        'jenis_mutasi',
        'posisi',
        'status',
    ];

    protected $casts = [
        'lev1' => 'integer',
        'lev2' => 'integer',
        'lev3' => 'integer',
        'lev4' => 'integer',
        'posisi' => 'integer',
    ];

    public function saldos()
    {
        return $this->hasMany(Saldo::class, 'kode_akun', 'kode_akun');
    }

    public function transaksiDebets()
    {
        return $this->hasMany(Transaksi::class, 'rekening_debet', 'kode_akun');
    }

    public function transaksiKredits()
    {
        return $this->hasMany(Transaksi::class, 'rekening_kredit', 'kode_akun');
    }
}
