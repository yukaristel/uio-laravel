<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\MenuMakanan;
use App\Http\Requests\StoreTransaksiRequest;
use App\Services\TransaksiService;

class TransaksiController extends Controller
{
    public function __construct(
        private TransaksiService $transaksiService
    ) {}

    public function index()
    {
        $transaksiList = TransaksiPenjualan::with('user')
                                            ->latest('tanggal_transaksi')
                                            ->paginate(15);
        return view('transaksi.index', compact('transaksiList'));
    }

    public function create()
    {
        $menuList = MenuMakanan::with(['kategori', 'hargaGrosirs'])
                                ->where('status', 'tersedia')
                                ->get();
        return view('transaksi.create', compact('menuList'));
    }

    public function store(StoreTransaksiRequest $request)
    {
        try {
            $transaksi = $this->transaksiService->createTransaksi($request->validated());
            return redirect()->route('transaksi.show', $transaksi)
                             ->with('success', 'Transaksi berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    public function show(TransaksiPenjualan $transaksi)
    {
        $transaksi->load('details.menu', 'user');
        return view('transaksi.show', compact('transaksi'));
    }

    public function struk(TransaksiPenjualan $transaksi)
    {
        $transaksi->load('details.menu', 'user');
        return view('transaksi.struk', compact('transaksi'));
    }
}
