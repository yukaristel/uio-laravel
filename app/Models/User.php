<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'username',
        'nama_lengkap',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function transaksiPenjualans()
    {
        return $this->hasMany(TransaksiPenjualan::class);
    }

    public function jurnals()
    {
        return $this->hasMany(Transaksi::class, 'id_user');
    }

    public function pembelians()
    {
        return $this->hasMany(PembelianBahan::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
