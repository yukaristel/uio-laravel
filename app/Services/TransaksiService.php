<?php

namespace App\Services;

use App\Models\TransaksiPenjualan;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\MenuMakanan;
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

            $menus = MenuMakanan::with('hargaGrosirs')
                                ->whereIn('id', collect($data['details'])->pluck('menu_id'))
                                ->get()
                                ->keyBy('id');

            $totalHargaNormal = 0;
            $totalModal       = 0;
            $resolvedDetails  = [];

            foreach ($data['details'] as $detail) {
                $menu   = $menus->get($detail['menu_id']);
                $qty    = (int) $detail['jumlah'];
                $modal  = (float) ($detail['harga_modal_satuan'] ?? ($menu->harga_modal ?? 0));

                $calc   = $menu ? $menu->hitungSubtotalDenganGrosir($qty)
                                : ['subtotal' => $qty * (float) ($detail['harga_satuan'] ?? 0), 'hemat' => 0];
                $subtotal  = $calc['subtotal'];
                $hargaSat  = $qty > 0 ? round($subtotal / $qty, 2) : 0;

                $totalHargaNormal += $subtotal;
                $totalModal       += $qty * $modal;

                $resolvedDetails[] = [
                    'menu_id'            => (int) $detail['menu_id'],
                    'jumlah'             => $qty,
                    'harga_satuan'       => $hargaSat,
                    'harga_modal_satuan' => $modal,
                    'subtotal'           => $subtotal,
                    'subtotal_modal'     => $qty * $modal,
                ];
            }

            if ($isTunai) {
                $totalHarga  = $totalHargaNormal;
                $uangBayar   = $data['uang_bayar'];
                $uangKembali = $uangBayar - $totalHarga;
            } else {
                $totalHarga  = $data['nominal_diterima'];
                $uangBayar   = $totalHarga;
                $uangKembali = 0;
            }

            $totalKeuntungan = $totalHarga - $totalModal;

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

            foreach ($resolvedDetails as $detail) {
                DetailTransaksi::create([
                    'transaksi_id'       => $transaksi->id,
                    'menu_id'            => $detail['menu_id'],
                    'jumlah'             => $detail['jumlah'],
                    'harga_satuan'       => $detail['harga_satuan'],
                    'harga_modal_satuan' => $detail['harga_modal_satuan'],
                    'subtotal'           => $detail['subtotal'],
                    'subtotal_modal'     => $detail['subtotal_modal'],
                ]);

                $this->stockService->deductStockFromMenu(
                    $detail['menu_id'],
                    $detail['jumlah'],
                    $transaksi->id
                );
            }

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
