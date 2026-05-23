<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\ChartOfAccount;
use App\Http\Requests\StoreJurnalRequest;
use Illuminate\Http\Request;

class JurnalController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with('user', 'rekeningDebet', 'rekeningKredit')
                           ->latest('tgl_transaksi');

        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tgl_transaksi', $request->bulan)
                  ->whereYear('tgl_transaksi', $request->tahun);
        } elseif ($request->tahun) {
            $query->whereYear('tgl_transaksi', $request->tahun);
        }

        $jurnalList = $query->paginate(20);
        $bulan      = $request->bulan ?? now()->month;
        $tahun      = $request->tahun ?? now()->year;

        return view('jurnal.index', compact('jurnalList', 'bulan', 'tahun'));
    }

    public function create()
    {
        $akunList = ChartOfAccount::where('status', 'Aktif')
                                   ->orderBy('kode_akun')
                                   ->get();
        return view('jurnal.create', compact('akunList'));
    }

    public function store(StoreJurnalRequest $request)
    {
        Transaksi::create([
            'tgl_transaksi'        => $request->tgl_transaksi,
            'rekening_debet'       => $request->rekening_debet,
            'rekening_kredit'      => $request->rekening_kredit,
            'keterangan_transaksi' => $request->keterangan_transaksi,
            'jumlah'               => $request->jumlah,
            'id_user'              => auth()->id(),
        ]);

        return redirect()->route('jurnal.index')
                         ->with('success', 'Jurnal berhasil dicatat!');
    }

    public function edit(Transaksi $jurnal)
    {
        $akunList = ChartOfAccount::where('status', 'Aktif')
                                   ->orderBy('kode_akun')
                                   ->get();
        return view('jurnal.edit', compact('jurnal', 'akunList'));
    }

    public function update(StoreJurnalRequest $request, Transaksi $jurnal)
    {
        $jurnal->update([
            'tgl_transaksi'        => $request->tgl_transaksi,
            'rekening_debet'       => $request->rekening_debet,
            'rekening_kredit'      => $request->rekening_kredit,
            'keterangan_transaksi' => $request->keterangan_transaksi,
            'jumlah'               => $request->jumlah,
        ]);

        return redirect()->route('jurnal.index')
                         ->with('success', 'Jurnal berhasil diupdate!');
    }

    public function destroy(Transaksi $jurnal)
    {
        $jurnal->delete();
        return redirect()->route('jurnal.index')
                         ->with('success', 'Jurnal berhasil dihapus!');
    }
}
