<?php

namespace App\Services;

use App\Models\TransaksiPenjualan;
use App\Models\BahanBaku;
use App\Models\Saldo;
use App\Models\ChartOfAccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanService
{
    public function getLaporanHarian(Carbon $date): array
    {
        return [
            'total_transaksi'   => TransaksiPenjualan::whereDate('tanggal_transaksi', $date)->count(),
            'total_penjualan'   => TransaksiPenjualan::whereDate('tanggal_transaksi', $date)->sum('total_harga'),
            'total_modal'       => TransaksiPenjualan::whereDate('tanggal_transaksi', $date)->sum('total_modal'),
            'total_keuntungan'  => TransaksiPenjualan::whereDate('tanggal_transaksi', $date)->sum('total_keuntungan'),
            'metode_pembayaran' => TransaksiPenjualan::whereDate('tanggal_transaksi', $date)
                                    ->select('metode_pembayaran', DB::raw('COUNT(*) as jumlah'), DB::raw('SUM(total_harga) as total'))
                                    ->groupBy('metode_pembayaran')
                                    ->get(),
            'transaksi_list'    => TransaksiPenjualan::with('user')
                                    ->whereDate('tanggal_transaksi', $date)
                                    ->latest()
                                    ->get(),
        ];
    }

    public function getLaporanBulanan(int $bulan, int $tahun): array
    {
        $startDate = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();

        return [
            'total_transaksi'  => TransaksiPenjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])->count(),
            'total_penjualan'  => TransaksiPenjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])->sum('total_harga'),
            'total_modal'      => TransaksiPenjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])->sum('total_modal'),
            'total_keuntungan' => TransaksiPenjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])->sum('total_keuntungan'),
            'daily_breakdown'  => TransaksiPenjualan::whereBetween('tanggal_transaksi', [$startDate, $endDate])
                                    ->select(
                                        DB::raw('DATE(tanggal_transaksi) as tanggal'),
                                        DB::raw('COUNT(*) as jumlah_transaksi'),
                                        DB::raw('SUM(total_harga) as total_penjualan'),
                                        DB::raw('SUM(total_keuntungan) as keuntungan')
                                    )
                                    ->groupBy('tanggal')
                                    ->orderBy('tanggal')
                                    ->get(),
        ];
    }

    public function getLaporanStok(): array
    {
        return [
            'stok_menipis'    => BahanBaku::whereRaw('stok_tersedia <= stok_minimum')->get(),
            'stok_normal'     => BahanBaku::whereRaw('stok_tersedia > stok_minimum')->get(),
            'total_nilai_stok' => BahanBaku::selectRaw('SUM(stok_tersedia * harga_beli_per_satuan) as total')->value('total'),
        ];
    }

    public function getNeraca(int $tahun): array
    {
        $akunNeraca = ChartOfAccount::where('posisi', 1)->where('status', 'Aktif')->get();
        $result     = [];

        foreach ($akunNeraca as $akun) {
            $saldo = Saldo::where('kode_akun', $akun->kode_akun)
                          ->where('tahun', $tahun)
                          ->selectRaw('SUM(debet) as total_debet, SUM(kredit) as total_kredit')
                          ->first();

            $result[] = [
                'akun'         => $akun,
                'total_debet'  => $saldo->total_debet ?? 0,
                'total_kredit' => $saldo->total_kredit ?? 0,
            ];
        }

        return $result;
    }

    public function getLabaRugi(int $bulan, int $tahun): array
    {
        $akunLabaRugi = ChartOfAccount::where('posisi', 2)->where('status', 'Aktif')->get();
        $result       = [];

        foreach ($akunLabaRugi as $akun) {
            $saldo = Saldo::where('kode_akun', $akun->kode_akun)
                          ->where('tahun', $tahun)
                          ->where('bulan', $bulan)
                          ->first();

            $result[] = [
                'akun'         => $akun,
                'total_debet'  => $saldo->debet ?? 0,
                'total_kredit' => $saldo->kredit ?? 0,
            ];
        }

        return $result;
    }
}
