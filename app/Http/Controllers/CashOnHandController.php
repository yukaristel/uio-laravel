<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\Transaksi;
use App\Http\Requests\StoreCashOnHandRequest;
use Illuminate\Support\Facades\DB;

class CashOnHandController extends Controller
{
    private const AKUN_KAS_TUNAI      = '1.1.01.01';
    private const AKUN_PENDAPATAN_LAIN = '4.1.01.99';
    private const AKUN_BEBAN_LAIN      = '5.3.99.99';

    public function index()
    {
        $saldoSistem = $this->getSaldoSistem();
        return view('cash-on-hand.index', [
            'saldoSistem' => $saldoSistem,
            'tanggal'     => today()->toDateString(),
        ]);
    }

    public function store(StoreCashOnHandRequest $request)
    {
        $pecahan = [
            100000 => (int) $request->jumlah_100000,
            50000  => (int) $request->jumlah_50000,
            20000  => (int) $request->jumlah_20000,
            10000  => (int) $request->jumlah_10000,
            5000   => (int) $request->jumlah_5000,
            2000   => (int) $request->jumlah_2000,
            1000   => (int) $request->jumlah_1000,
            500    => (int) $request->jumlah_500,
            200    => (int) $request->jumlah_200,
            100    => (int) $request->jumlah_100,
        ];

        $totalPecahan = 0;
        foreach ($pecahan as $nominal => $jml) {
            $totalPecahan += $nominal * $jml;
        }
        $totalFisik = $totalPecahan + (float) ($request->nominal_lain ?? 0);

        $saldoSistem = $this->getSaldoSistem();
        $selisih     = $totalFisik - $saldoSistem;

        if (abs($selisih) < 0.01) {
            return redirect()->route('cash-on-hand.index')
                             ->with('success', 'Kas on hand sudah sesuai dengan catatan sistem. Total: ' . $this->rp($totalFisik));
        }

        if (! $request->boolean('konfirmasi')) {
            return back()->withInput()->with('warning', 'Terdapat selisih kas. Silakan periksa ulang atau konfirmasi untuk mencatat selisih.');
        }

        $jumlah = (int) round(abs($selisih));

        if ($selisih > 0) {
            $debet  = self::AKUN_KAS_TUNAI;
            $kredit = self::AKUN_PENDAPATAN_LAIN;
            $ket    = 'Selisih kas on hand (lebih) - ' . ($request->keterangan ?: 'Cash on Hand ' . now()->toDateString());
        } else {
            $debet  = self::AKUN_BEBAN_LAIN;
            $kredit = self::AKUN_KAS_TUNAI;
            $ket    = 'Selisih kas on hand (kurang) - ' . ($request->keterangan ?: 'Cash on Hand ' . now()->toDateString());
        }

        DB::transaction(function () use ($debet, $kredit, $jumlah, $ket) {
            Transaksi::create([
                'tgl_transaksi'        => today()->toDateString(),
                'rekening_debet'       => $debet,
                'rekening_kredit'      => $kredit,
                'keterangan_transaksi' => $ket,
                'jumlah'               => $jumlah,
                'id_user'              => auth()->id(),
            ]);
        });

        return redirect()->route('cash-on-hand.index')
                         ->with('success', 'Selisih kas berhasil dicatat: ' . ($selisih > 0 ? 'lebih' : 'kurang') . ' ' . $this->rp($jumlah));
    }

    private function rp(float $n): string
    {
        return 'Rp ' . number_format($n, 0, ',', '.');
    }

    private function getSaldoSistem(): float
    {
        $tahun = (int) today()->year;
        $bulan = (int) today()->month;

        $saldo = Saldo::where('kode_akun', self::AKUN_KAS_TUNAI)
                      ->where('tahun', $tahun)
                      ->where('bulan', $bulan)
                      ->first();

        if (! $saldo) {
            return 0.0;
        }

        return (float) (($saldo->debet ?? 0) - ($saldo->kredit ?? 0));
    }
}
