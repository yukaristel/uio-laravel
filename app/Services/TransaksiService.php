<?php

namespace App\Services;

use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;

class TransaksiService
{
    public function __construct(
        private StockService $stockService
    ) {}

    private string $rekeningPendapatan = '4.1.01.01';

    private function getRekeningKas(string $metodePembayaran): string
    {
        $keyword = match(strtolower($metodePembayaran)) {
            'tunai'     => 'Tunai',
            'qris'      => 'QRIS',
            'gopay'     => 'GoPay',
            'grab'      => 'Grab',
            'debit'     => 'Mandiri',
            'transfer'  => 'Mandiri',
            'shopeepay' => 'Lain-lain',
            default     => 'Lain-lain',
        };

        $akun = ChartOfAccount::where('kode_akun', 'like', '1.1.%')
                               ->where('lev4', '>', 0)
                               ->where('nama_akun', 'like', '%' . $keyword . '%')
                               ->where('status', 'Aktif')
                               ->first();

        return $akun?->kode_akun ?? '1.1.01.99';
    }

    public function createTransaksi(array $data): TransaksiPenjualan
    {
        return DB::transaction(function () use ($data) {
            $isTunai = $data['metode_pembayaran'] === 'tunai';

            // 1. Hitung total normal (dari item)
            $totalHargaNormal = 0;
            $totalModal       = 0;

            foreach ($data['details'] as $detail) {
                $totalHargaNormal += $detail['jumlah'] * $detail['harga_satuan'];
                $totalModal       += $detail['jumlah'] * $detail['harga_modal_satuan'];
            }

            // 2. Total harga final & kembalian
            if ($isTunai) {
                $totalHarga  = $totalHargaNormal;
                $uangBayar   = $data['uang_bayar'];
                $uangKembali = $uangBayar - $totalHarga;
            } else {
                // Non-tunai: total = nominal yang diterima
                $totalHarga  = $data['nominal_diterima'];
                $uangBayar   = $totalHarga;
                $uangKembali = 0;
            }

            $totalKeuntungan = $totalHarga - $totalModal;

            // 3. Catat transaksi penjualan
            $transaksi = TransaksiPenjualan::create([
                'no_transaksi'      => $this->generateNoTransaksi(),
                'tanggal_transaksi' => $data['tanggal_transaksi'],
                'total_harga'       => $totalHarga,
                'total_modal'       => $totalModal,
                'total_keuntungan'  => $totalKeuntungan,
                'metode_pembayaran' => $data['metode_pembayaran'],
                'uang_bayar'        => $uangBayar,
                'uang_kembali'      => $uangKembali,
                'user_id'           => auth()->id(),
            ]);

            // 4. Catat detail & kurangi stok
            foreach ($data['details'] as $detail) {
                DetailTransaksi::create([
                    'transaksi_id'       => $transaksi->id,
                    'menu_id'            => $detail['menu_id'],
                    'jumlah'             => $detail['jumlah'],
                    'harga_satuan'       => $detail['harga_satuan'],
                    'harga_modal_satuan' => $detail['harga_modal_satuan'],
                    'subtotal'           => $detail['jumlah'] * $detail['harga_satuan'],
                    'subtotal_modal'     => $detail['jumlah'] * $detail['harga_modal_satuan'],
                ]);

                $this->stockService->deductStockFromMenu(
                    $detail['menu_id'],
                    $detail['jumlah'],
                    $transaksi->id
                );
            }

            // 5. Catat jurnal akuntansi
            Transaksi::create([
                'tgl_transaksi'        => now()->toDateString(),
                'rekening_debet'       => $this->getRekeningKas($data['metode_pembayaran']),
                'rekening_kredit'      => $this->rekeningPendapatan,
                'keterangan_transaksi' => 'Penjualan - ' . $transaksi->no_transaksi .
                                          ($isTunai ? '' : ' (via ' . strtoupper($data['metode_pembayaran']) . ')'),
                'jumlah'               => (int) $totalHarga,
                'id_user'              => auth()->id(),
            ]);

            return $transaksi;
        });
    }

    public function generateNoTransaksi(): string
    {
        $tanggal = date('Ymd');
        $lastNum = TransaksiPenjualan::whereDate('tanggal_transaksi', today())->count();
        $newNum  = $lastNum + 1;
        return 'TRX' . $tanggal . str_pad($newNum, 3, '0', STR_PAD_LEFT);
    }
}
