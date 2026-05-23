<?php

namespace App\Http\Controllers;

use App\Models\PembelianBahan;
use App\Models\BahanBaku;
use App\Models\Transaksi;
use App\Http\Requests\StorePembelianBahanRequest;
use App\Services\StockService;
use Illuminate\Support\Facades\DB;

class PembelianBahanController extends Controller
{
    public function __construct(
        private StockService $stockService
    ) {}

    public function index()
    {
        $pembelianList = PembelianBahan::with('bahanBaku', 'user')
                                        ->latest('tanggal_beli')
                                        ->paginate(15);
        return view('pembelian-bahan.index', compact('pembelianList'));
    }

    public function create()
    {
        $bahanList = BahanBaku::orderBy('nama_bahan')->get();
        return view('pembelian-bahan.create', compact('bahanList'));
    }

    public function store(StorePembelianBahanRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Catat pembelian
            $pembelian = PembelianBahan::create([
                'bahan_id'          => $request->bahan_id,
                'jumlah_beli'       => $request->jumlah_beli,
                'harga_beli_satuan' => $request->harga_beli_satuan,
                'total_harga'       => $request->jumlah_beli * $request->harga_beli_satuan,
                'supplier'          => $request->supplier,
                'tanggal_beli'      => $request->tanggal_beli,
                'user_id'           => auth()->id(),
            ]);

            // 2. Tambah stok bahan
            $this->stockService->addStockFromPurchase(
                $request->bahan_id,
                $request->jumlah_beli,
                $request->harga_beli_satuan,
                $pembelian->id
            );

            // 3. Catat jurnal akuntansi
            Transaksi::create([
                'tgl_transaksi'        => $request->tanggal_beli,
                'rekening_debet'       => '1.2.01.00',
                'rekening_kredit'      => $request->rekening_bayar,
                'keterangan_transaksi' => 'Pembelian bahan: ' . BahanBaku::find($request->bahan_id)->nama_bahan . ' dari ' . ($request->supplier ?? '-'),
                'jumlah'               => (int) ($request->jumlah_beli * $request->harga_beli_satuan),
                'id_user'              => auth()->id(),
            ]);
        });

        return redirect()->route('pembelian-bahan.index')
                         ->with('success', 'Pembelian bahan berhasil dicatat!');
    }

    public function show(PembelianBahan $pembelianBahan)
    {
        $pembelianBahan->load('bahanBaku', 'user');
        return view('pembelian-bahan.show', compact('pembelianBahan'));
    }
}
