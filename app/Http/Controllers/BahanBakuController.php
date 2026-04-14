<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use App\Http\Requests\StoreBahanBakuRequest;
use App\Http\Requests\UpdateBahanBakuRequest;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahanList = BahanBaku::latest()->paginate(15);
        return view('bahan-baku.index', compact('bahanList'));
    }

    public function create()
    {
        return view('bahan-baku.create');
    }

    public function store(StoreBahanBakuRequest $request)
    {
        BahanBaku::create($request->validated());
        return redirect()->route('bahan-baku.index')
                         ->with('success', 'Bahan baku berhasil ditambahkan!');
    }

    public function show(BahanBaku $bahanBaku)
    {
        $history = $bahanBaku->stockMovements()->latest()->paginate(10);
        return view('bahan-baku.show', compact('bahanBaku', 'history'));
    }

    public function edit(BahanBaku $bahanBaku)
    {
        return view('bahan-baku.edit', compact('bahanBaku'));
    }

    public function update(UpdateBahanBakuRequest $request, BahanBaku $bahanBaku)
    {
        $bahanBaku->update($request->validated());
        return redirect()->route('bahan-baku.index')
                         ->with('success', 'Bahan baku berhasil diubah!');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        $bahanBaku->delete();
        return redirect()->route('bahan-baku.index')
                         ->with('success', 'Bahan baku berhasil dihapus!');
    }
}
