<?php

namespace App\Services;

use App\Models\BahanBaku;
use App\Models\StockMovement;
use App\Models\MenuMakanan;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function deductStockFromMenu(int $menuId, int $quantity, int $transaksiId): void
    {
        $menu = MenuMakanan::with('reseps.bahanBaku')->findOrFail($menuId);

        foreach ($menu->reseps as $resep) {
            $jumlahBahan = $resep->jumlah_bahan * $quantity;
            $this->deductStock(
                bahanId: $resep->bahan_id,
                jumlah: $jumlahBahan,
                satuan: $resep->satuan,
                referensiType: 'penjualan',
                referensiId: $transaksiId,
                keterangan: "Penjualan - Menu ID: {$menuId} (x{$quantity} porsi)"
            );
        }
    }

    public function addStockFromPurchase(int $bahanId, float $jumlah, float $hargaPerSatuan, int $pembelianId): void
    {
        $this->addStock(
            bahanId: $bahanId,
            jumlah: $jumlah,
            hargaPerSatuan: $hargaPerSatuan,
            referensiType: 'pembelian',
            referensiId: $pembelianId,
            keterangan: 'Pembelian bahan'
        );
    }

    public function addStockManual(int $bahanId, float $jumlah, float $hargaPerSatuan, string $keterangan): void
    {
        $this->addStock(
            bahanId: $bahanId,
            jumlah: $jumlah,
            hargaPerSatuan: $hargaPerSatuan,
            referensiType: 'manual',
            referensiId: null,
            keterangan: $keterangan
        );
    }

    private function addStock(int $bahanId, float $jumlah, float $hargaPerSatuan, string $referensiType, ?int $referensiId, ?string $keterangan): void
    {
        DB::transaction(function () use ($bahanId, $jumlah, $hargaPerSatuan, $referensiType, $referensiId, $keterangan) {
            $bahan       = BahanBaku::findOrFail($bahanId);
            $stokSebelum = $bahan->stok_tersedia;
            $stokSesudah = $stokSebelum + $jumlah;

            $hargaBaru = $this->calculateWeightedAverage(
                $stokSebelum,
                $bahan->harga_beli_per_satuan,
                $jumlah,
                $hargaPerSatuan
            );

            $bahan->update([
                'stok_tersedia'         => $stokSesudah,
                'harga_beli_per_satuan' => $hargaBaru,
            ]);

            StockMovement::create([
                'bahan_id'         => $bahanId,
                'jenis_pergerakan' => 'masuk',
                'jumlah'           => $jumlah,
                'satuan'           => $bahan->satuan,
                'harga_per_satuan' => $hargaPerSatuan,
                'total_nilai'      => $jumlah * $hargaPerSatuan,
                'stok_sebelum'     => $stokSebelum,
                'stok_sesudah'     => $stokSesudah,
                'referensi_type'   => $referensiType,
                'referensi_id'     => $referensiId,
                'keterangan'       => $keterangan,
                'user_id'          => auth()->id(),
            ]);
        });
    }

    private function deductStock(int $bahanId, float $jumlah, string $satuan, string $referensiType, ?int $referensiId, ?string $keterangan): void
    {
        DB::transaction(function () use ($bahanId, $jumlah, $satuan, $referensiType, $referensiId, $keterangan) {
            $bahan       = BahanBaku::findOrFail($bahanId);
            $stokSebelum = $bahan->stok_tersedia;
            $stokSesudah = max(0, $stokSebelum - $jumlah);

            $bahan->update(['stok_tersedia' => $stokSesudah]);

            StockMovement::create([
                'bahan_id'         => $bahanId,
                'jenis_pergerakan' => 'keluar',
                'jumlah'           => $jumlah,
                'satuan'           => $satuan,
                'harga_per_satuan' => $bahan->harga_beli_per_satuan,
                'total_nilai'      => $jumlah * $bahan->harga_beli_per_satuan,
                'stok_sebelum'     => $stokSebelum,
                'stok_sesudah'     => $stokSesudah,
                'referensi_type'   => $referensiType,
                'referensi_id'     => $referensiId,
                'keterangan'       => $keterangan,
                'user_id'          => auth()->id(),
            ]);
        });
    }

    private function calculateWeightedAverage(float $stokLama, float $hargaLama, float $stokBaru, float $hargaBaru): float
    {
        if ($stokLama + $stokBaru == 0) return $hargaBaru;
        return (($stokLama * $hargaLama) + ($stokBaru * $hargaBaru)) / ($stokLama + $stokBaru);
    }
}
