<?php

namespace App\Http\Controllers;

use App\Services\LaporanService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function __construct(
        private LaporanService $laporanService
    ) {}

    public function harian(Request $request)
    {
        $tanggal = $request->tanggal
                    ? Carbon::parse($request->tanggal)
                    : Carbon::today();

        $data = $this->laporanService->getLaporanHarian($tanggal);

        return view('laporan.harian', compact('data', 'tanggal'));
    }

    public function bulanan(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $data = $this->laporanService->getLaporanBulanan($bulan, $tahun);

        return view('laporan.bulanan', compact('data', 'bulan', 'tahun'));
    }

    public function stok()
    {
        $data = $this->laporanService->getLaporanStok();
        return view('laporan.stok', compact('data'));
    }

    public function neraca(Request $request)
    {
        $bulan = $request->bulan ?? 0;
        $tahun = $request->tahun ?? now()->year;
        $data  = $this->laporanService->getNeraca($bulan, $tahun);

        if ($request->cetak) {
            return view('laporan.cetak.neraca', compact('data', 'bulan', 'tahun'));
        }

        return view('laporan.neraca', compact('data', 'bulan', 'tahun'));
    }

    public function labarugi(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $data  = $this->laporanService->getLabaRugi($bulan, $tahun);

        if ($request->cetak) {
            return view('laporan.cetak.labarugi', compact('data', 'bulan', 'tahun'));
        }

        return view('laporan.labarugi', compact('data', 'bulan', 'tahun'));
    }

    public function jurnal(Request $request)
    {
        $mode    = $request->mode ?? 'harian';
        $tanggal = $request->tanggal ?? now()->format('Y-m-d');
        $bulan   = $request->bulan ?? now()->month;
        $tahun   = $request->tahun ?? now()->year;
        $data    = $this->laporanService->getJurnalTransaksi($tanggal, $bulan, $tahun, $mode);

        if ($request->cetak) {
            return view('laporan.cetak.jurnal', compact('data', 'mode', 'tanggal', 'bulan', 'tahun'));
        }

        return view('laporan.jurnal', compact('data', 'mode', 'tanggal', 'bulan', 'tahun'));
    }
}
