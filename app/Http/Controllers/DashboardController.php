<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\MenuMakanan;
use App\Models\TransaksiPenjualan;
use App\Models\AsetTetap;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // Transaksi hari ini
        $transaksiHariIni = TransaksiPenjualan::whereDate('tanggal_transaksi', $today)->count();
        $pendapatanHariIni = TransaksiPenjualan::whereDate('tanggal_transaksi', $today)->sum('total_harga');
        $keuntunganHariIni = TransaksiPenjualan::whereDate('tanggal_transaksi', $today)->sum('total_keuntungan');

        // Transaksi bulan ini
        $pendapatanBulanIni = TransaksiPenjualan::whereMonth('tanggal_transaksi', $today->month)
                                                ->whereYear('tanggal_transaksi', $today->year)
                                                ->sum('total_harga');

        // Stok menipis
        $stokMenuipis = BahanBaku::whereRaw('stok_tersedia <= stok_minimum')->count();

        // Total menu tersedia
        $totalMenu = MenuMakanan::where('status', 'tersedia')->count();

        // Total aset aktif
        $totalAset = AsetTetap::whereIn('status', ['Baik', 'Maintenance'])->count();

        // Transaksi terakhir
        $transaksiTerakhir = TransaksiPenjualan::with('user')
                                                ->latest('tanggal_transaksi')
                                                ->take(5)
                                                ->get();

        return view('dashboard.index', compact(
            'transaksiHariIni',
            'pendapatanHariIni',
            'keuntunganHariIni',
            'pendapatanBulanIni',
            'stokMenuipis',
            'totalMenu',
            'totalAset',
            'transaksiTerakhir',
        ));
    }
}
