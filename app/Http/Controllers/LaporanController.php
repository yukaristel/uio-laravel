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
        $tahun = $request->tahun ?? now()->year;
        $data  = $this->laporanService->getNeraca($tahun);
        return view('laporan.neraca', compact('data', 'tahun'));
    }

    public function labarugi(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;
        $data  = $this->laporanService->getLabaRugi($bulan, $tahun);
        return view('laporan.labarugi', compact('data', 'bulan', 'tahun'));
    }
}
