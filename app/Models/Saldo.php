<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;

    protected $table = 'saldo';

    public $incrementing = false;
    public $timestamps = false;

    protected $keyType = 'string';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'kode_akun',
        'tahun',
        'bulan',
        'debet',
        'kredit',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'bulan' => 'integer',
        'debet' => 'integer',
        'kredit' => 'integer',
    ];

    public function akun()
    {
        return $this->belongsTo(ChartOfAccount::class, 'kode_akun', 'kode_akun');
    }
}
