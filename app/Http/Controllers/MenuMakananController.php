<?php

namespace App\Http\Controllers;

use App\Models\MenuMakanan;
use App\Models\KategoriMenu;
use App\Models\BahanBaku;
use App\Http\Requests\StoreMenuMakananRequest;
use App\Http\Requests\UpdateMenuMakananRequest;

class MenuMakananController extends Controller
{
    public function index()
    {
        $menuList = MenuMakanan::with('kategori')->latest()->paginate(15);
        return view('menu-makanan.index', compact('menuList'));
    }

    public function create()
    {
        $kategoriList = KategoriMenu::all();
        return view('menu-makanan.create', compact('kategoriList'));
    }

    public function store(StoreMenuMakananRequest $request)
    {
        MenuMakanan::create($request->validated());
        return redirect()->route('menu-makanan.index')
                         ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function show(MenuMakanan $menuMakanan)
    {
        $menuMakanan->load('kategori', 'reseps.bahanBaku');
        $bahanList = BahanBaku::all();
        return view('menu-makanan.show', compact('menuMakanan', 'bahanList'));
    }

    public function edit(MenuMakanan $menuMakanan)
    {
        $kategoriList = KategoriMenu::all();
        return view('menu-makanan.edit', compact('menuMakanan', 'kategoriList'));
    }

    public function update(UpdateMenuMakananRequest $request, MenuMakanan $menuMakanan)
    {
        $menuMakanan->update($request->validated());
        return redirect()->route('menu-makanan.index')
                         ->with('success', 'Menu berhasil diubah!');
    }

    public function destroy(MenuMakanan $menuMakanan)
    {
        $menuMakanan->delete();
        return redirect()->route('menu-makanan.index')
                         ->with('success', 'Menu berhasil dihapus!');
    }
}
