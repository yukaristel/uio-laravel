<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Http\Requests\StoreStockOpnameRequest;
use App\Services\StockService;
use Illuminate\Support\Str;

class StockController extends Controller
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function movement()
    {
        $movements = StockMovement::with('bahanBaku', 'user')
                                   ->latest()
                                   ->paginate(20);
        return view('stock.movement', compact('movements'));
    }

    public function opname()
    {
        $bahanList  = BahanBaku::all();
        $opnameList = StockOpname::with('bahanBaku', 'user')
                                  ->latest()
                                  ->paginate(15);
        return view('stock.opname', compact('bahanList', 'opnameList'));
    }

    public function storeOpname(StoreStockOpnameRequest $request)
    {
        $bahan      = BahanBaku::findOrFail($request->bahan_id);
        $stokSistem = $bahan->stok_tersedia;
        $stokFisik  = $request->stok_fisik;
        $selisih    = $stokFisik - $stokSistem;
        $nilaiSelisih = abs($selisih) * $bahan->harga_beli_per_satuan;

        StockOpname::create([
            'no_opname'       => 'OPN' . date('Ymd') . strtoupper(Str::random(4)),
            'tanggal_opname'  => $request->tanggal_opname,
            'bahan_id'        => $request->bahan_id,
            'stok_sistem'     => $stokSistem,
            'stok_fisik'      => $stokFisik,
            'selisih'         => $selisih,
            'satuan'          => $bahan->satuan,
            'harga_per_satuan'=> $bahan->harga_beli_per_satuan,
            'nilai_selisih'   => $nilaiSelisih,
            'jenis_selisih'   => $request->jenis_selisih,
            'keterangan'      => $request->keterangan,
            'status'          => 'draft',
            'user_id'         => auth()->id(),
        ]);

        // Update stok bahan sesuai stok fisik
        $bahan->update(['stok_tersedia' => $stokFisik]);

        // Catat stock movement
        StockMovement::create([
            'bahan_id'         => $bahan->id,
            'jenis_pergerakan' => 'opname',
            'jumlah'           => abs($selisih),
            'satuan'           => $bahan->satuan,
            'harga_per_satuan' => $bahan->harga_beli_per_satuan,
            'total_nilai'      => $nilaiSelisih,
            'stok_sebelum'     => $stokSistem,
            'stok_sesudah'     => $stokFisik,
            'referensi_type'   => 'opname',
            'keterangan'       => $request->keterangan ?? 'Stock opname',
            'user_id'          => auth()->id(),
        ]);

        return redirect()->route('stock.opname')
                         ->with('success', 'Stock opname berhasil dicatat!');
    }
}
