<?php

namespace App\Http\Controllers;

use App\Models\AsetTetap;
use App\Http\Requests\StoreAsetTetapRequest;
use App\Http\Requests\UpdateAsetTetapRequest;
use App\Services\AsetTetapService;
use Illuminate\Http\Request;

class AsetTetapController extends Controller
{
    public function __construct(
        private AsetTetapService $asetTetapService
    ) {}

    public function index()
    {
        $asetList = AsetTetap::latest()->paginate(15);
        return view('aset-tetap.index', compact('asetList'));
    }

    public function create()
    {
        return view('aset-tetap.create');
    }

    public function store(StoreAsetTetapRequest $request)
    {
        try {
            $this->asetTetapService->beliAset($request->validated());
            return redirect()->route('aset-tetap.index')
                             ->with('success', 'Aset berhasil ditambahkan & jurnal dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan aset: ' . $e->getMessage());
        }
    }

    public function show(AsetTetap $asetTetap)
    {
        return view('aset-tetap.show', compact('asetTetap'));
    }

    public function edit(AsetTetap $asetTetap)
    {
        return view('aset-tetap.edit', compact('asetTetap'));
    }

    public function update(UpdateAsetTetapRequest $request, AsetTetap $asetTetap)
    {
        $asetTetap->update($request->validated());
        return redirect()->route('aset-tetap.index')
                         ->with('success', 'Aset berhasil diubah!');
    }

    public function destroy(AsetTetap $asetTetap)
    {
        $asetTetap->delete();
        return redirect()->route('aset-tetap.index')
                         ->with('success', 'Aset berhasil dihapus!');
    }

    public function penyusutan(Request $request, AsetTetap $asetTetap)
    {
        try {
            $this->asetTetapService->catatPenyusutan($asetTetap, $request->tanggal);
            return back()->with('success', 'Penyusutan berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mencatat penyusutan: ' . $e->getMessage());
        }
    }

    public function lepas(Request $request, AsetTetap $asetTetap)
    {
        $request->validate([
            'status_baru' => 'required|in:Dijual,Hapus',
            'harga_jual'  => 'nullable|numeric|min:0',
            'tanggal'     => 'nullable|date',
        ]);

        try {
            $this->asetTetapService->lepasAset(
                $asetTetap,
                $request->status_baru,
                $request->harga_jual ?? 0,
                $request->tanggal
            );
            return redirect()->route('aset-tetap.index')
                             ->with('success', 'Aset berhasil dilepas & jurnal dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal melepas aset: ' . $e->getMessage());
        }
    }
}
