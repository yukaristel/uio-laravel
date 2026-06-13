<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuMakanan extends Model
{
    use HasFactory;

    protected $table = 'menu_makanan';

    protected $fillable = [
        'kode_menu',
        'nama_menu',
        'kategori_id',
        'harga_modal',
        'harga_jual',
        'margin_keuntungan',
        'status',
        'foto_menu',
    ];

    protected $casts = [
        'harga_modal' => 'decimal:2',
        'harga_jual' => 'decimal:2',
        'margin_keuntungan' => 'decimal:2',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriMenu::class, 'kategori_id');
    }

    public function reseps()
    {
        return $this->hasMany(ResepMenu::class, 'menu_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'menu_id');
    }

    public function hargaGrosirs()
    {
        return $this->hasMany(MenuHargaGrosir::class, 'menu_id');
    }

    public function getTierTertinggiUntukQty(int $qty)
    {
        if ($qty < 2) {
            return null;
        }
        return $this->hargaGrosirs()
                    ->where('min_qty', '<=', $qty)
                    ->get()
                    ->sortByDesc('min_qty')
                    ->first();
    }

    public function hitungSubtotalDenganGrosir(int $qty): array
    {
        $hargaNormal = (float) $this->harga_jual;
        $tier        = $this->getTierTertinggiUntukQty($qty);

        if (!$tier) {
            return [
                'tier'         => null,
                'min_qty'      => null,
                'bundle_count' => 0,
                'sisa_qty'     => $qty,
                'subtotal'     => $qty * $hargaNormal,
                'hemat'        => 0,
            ];
        }

        $minQty       = (int) $tier->min_qty;
        $hargaBundle  = (float) $tier->harga_total;
        $bundleCount  = intdiv($qty, $minQty);
        $sisaQty      = $qty % $minQty;
        $subtotalTier = $bundleCount * $hargaBundle;
        $subtotalSisa = $sisaQty * $hargaNormal;
        $subtotal     = $subtotalTier + $subtotalSisa;
        $subtotalNorm = $qty * $hargaNormal;
        $hemat        = $subtotalNorm - $subtotal;

        return [
            'tier'         => $tier,
            'min_qty'      => $minQty,
            'bundle_count' => $bundleCount,
            'sisa_qty'     => $sisaQty,
            'subtotal'     => $subtotal,
            'hemat'        => $hemat,
        ];
    }
}
