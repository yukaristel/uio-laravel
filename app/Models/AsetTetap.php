<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsetTetap extends Model
{
    use HasFactory;

    protected $table = 'aset_tetap';
    protected $primaryKey = 'id_aset';

    protected $fillable = [
        'nama_barang',
        'tgl_beli',
        'unit',
        'harsat',
        'umur_ekonomis',
        'akumulasi_penyusutan',
        'jenis',
        'metode_beli',
        'status',
        'tgl_validasi',
        'keterangan',
    ];

    protected $casts = [
        'tgl_beli' => 'date',
        'tgl_validasi' => 'date',
        'harsat' => 'decimal:2',
        'akumulasi_penyusutan' => 'decimal:2',
        'nilai_total' => 'decimal:2',
        'nilai_buku' => 'decimal:2',
    ];
}
