<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Models\StockMovement;
use App\Models\StockOpname;
use App\Http\Requests\StoreStockOpnameRequest;
use App\Services\StockService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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
        $bahan        = BahanBaku::findOrFail($request->bahan_id);
        $stokSistem   = $bahan->stok_tersedia;
        $stokFisik    = $request->stok_fisik;
        $selisih      = $stokFisik - $stokSistem;
        $nilaiSelisih = abs($selisih) * $bahan->harga_beli_per_satuan;

        DB::transaction(function () use ($request, $bahan, $stokSistem, $stokFisik, $selisih, $nilaiSelisih) {

            // 1. Catat stock opname
            StockOpname::create([
                'no_opname'        => 'OPN' . date('Ymd') . strtoupper(\Illuminate\Support\Str::random(4)),
                'tanggal_opname'   => $request->tanggal_opname,
                'bahan_id'         => $request->bahan_id,
                'stok_sistem'      => $stokSistem,
                'stok_fisik'       => $stokFisik,
                'selisih'          => $selisih,
                'satuan'           => $bahan->satuan,
                'harga_per_satuan' => $bahan->harga_beli_per_satuan,
                'nilai_selisih'    => $nilaiSelisih,
                'jenis_selisih'    => $request->jenis_selisih,
                'keterangan'       => $request->keterangan,
                'status'           => 'draft',
                'user_id'          => auth()->id(),
            ]);

            // 2. Update stok
            $bahan->update(['stok_tersedia' => $stokFisik]);

            // 3. Catat stock movement
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

            // 4. Catat jurnal akuntansi (hanya jika ada selisih)
            if ($selisih != 0 && $nilaiSelisih > 0) {
                if ($selisih < 0) {
                    // Stok berkurang → Beban Kerugian Stok
                    \App\Models\Transaksi::create([
                        'tgl_transaksi'        => $request->tanggal_opname,
                        'rekening_debet'       => '5.3.03.01',
                        'rekening_kredit'      => '1.2.01.00',
                        'keterangan_transaksi' => 'Kerugian stok opname: ' . $bahan->nama_bahan . ' (' . $request->jenis_selisih . ')',
                        'jumlah'               => (int) $nilaiSelisih,
                        'id_user'              => auth()->id(),
                    ]);
                } else {
                    // Stok bertambah → Pendapatan Selisih Stok
                    \App\Models\Transaksi::create([
                        'tgl_transaksi'        => $request->tanggal_opname,
                        'rekening_debet'       => '1.2.01.00',
                        'rekening_kredit'      => '4.1.02.02',
                        'keterangan_transaksi' => 'Pendapatan selisih stok opname: ' . $bahan->nama_bahan,
                        'jumlah'               => (int) $nilaiSelisih,
                        'id_user'              => auth()->id(),
                    ]);
                }
            }
        });

        return redirect()->route('stock.opname')
                         ->with('success', 'Stock opname berhasil dicatat!');
    }
}
