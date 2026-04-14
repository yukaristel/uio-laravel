<?php

namespace App\Services;

use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TransaksiService
{
    public function __construct(
        private StockService $stockService
    ) {}

    private array $rekeningMap = [
        'tunai'    => '1.1.01.01',
        'qris'     => '1.1.01.02',
        'debit'    => '1.1.02.01',
        'transfer' => '1.1.02.01',
    ];

    private string $rekeningPendapatan = '4.1.01.01';

    public function createTransaksi(array $data): TransaksiPenjualan
    {
        return DB::transaction(function () use ($data) {
            // 1. Hitung total
            $totalHarga    = 0;
            $totalModal    = 0;

            foreach ($data['details'] as $detail) {
                $totalHarga += $detail['jumlah'] * $detail['harga_satuan'];
                $totalModal += $detail['jumlah'] * $detail['harga_modal_satuan'];
            }

            $totalKeuntungan = $totalHarga - $totalModal;
            $uangKembali     = $data['uang_bayar'] - $totalHarga;

            // 2. Catat transaksi penjualan
            $transaksi = TransaksiPenjualan::create([
                'no_transaksi'      => $this->generateNoTransaksi(),
                'tanggal_transaksi' => $data['tanggal_transaksi'],
                'total_harga'       => $totalHarga,
                'total_modal'       => $totalModal,
                'total_keuntungan'  => $totalKeuntungan,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'uang_bayar'        => $data['uang_bayar'],
                'uang_kembali'      => $uangKembali,
                'user_id'           => auth()->id(),
            ]);

            // 3. Catat detail & kurangi stok
            foreach ($data['details'] as $detail) {
                DetailTransaksi::create([
                    'transaksi_id'        => $transaksi->id,
                    'menu_id'             => $detail['menu_id'],
                    'jumlah'              => $detail['jumlah'],
                    'harga_satuan'        => $detail['harga_satuan'],
                    'harga_modal_satuan'  => $detail['harga_modal_satuan'],
                    'subtotal'            => $detail['jumlah'] * $detail['harga_satuan'],
                    'subtotal_modal'      => $detail['jumlah'] * $detail['harga_modal_satuan'],
                ]);

                $this->stockService->deductStockFromMenu($detail['menu_id'], $detail['jumlah'], $transaksi->id);
            }

            // 4. Catat jurnal akuntansi → trigger otomatis update saldo
            Transaksi::create([
                'tgl_transaksi'        => now()->toDateString(),
                'rekening_debet'       => $this->rekeningMap[$data['metode_pembayaran']] ?? '1.1.01.99',
                'rekening_kredit'      => $this->rekeningPendapatan,
                'keterangan_transaksi' => 'Penjualan - ' . $transaksi->no_transaksi,
                'jumlah'               => (int) $totalHarga,
                'id_user'              => auth()->id(),
            ]);

            return $transaksi;
        });
    }

    public function generateNoTransaksi(): string
    {
        $tanggal = date('Ymd');
        $lastNum = TransaksiPenjualan::whereDate('tanggal_transaksi', today())
                                      ->count();
        $newNum  = $lastNum + 1;
        return 'TRX' . $tanggal . str_pad($newNum, 3, '0', STR_PAD_LEFT);
    }
}
