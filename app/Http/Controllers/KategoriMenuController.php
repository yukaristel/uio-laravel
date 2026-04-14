<?php

namespace App\Http\Controllers;

use App\Models\KategoriMenu;
use App\Http\Requests\StoreKategoriMenuRequest;

class KategoriMenuController extends Controller
{
    public function index()
    {
        $kategoriList = KategoriMenu::withCount('menus')->latest()->paginate(15);
        return view('kategori-menu.index', compact('kategoriList'));
    }

    public function create()
    {
        return view('kategori-menu.create');
    }

    public function store(StoreKategoriMenuRequest $request)
    {
        KategoriMenu::create($request->validated());
        return redirect()->route('kategori-menu.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(KategoriMenu $kategoriMenu)
    {
        return view('kategori-menu.edit', compact('kategoriMenu'));
    }

    public function update(StoreKategoriMenuRequest $request, KategoriMenu $kategoriMenu)
    {
        $kategoriMenu->update($request->validated());
        return redirect()->route('kategori-menu.index')
                         ->with('success', 'Kategori berhasil diubah!');
    }

    public function destroy(KategoriMenu $kategoriMenu)
    {
        $kategoriMenu->delete();
        return redirect()->route('kategori-menu.index')
                         ->with('success', 'Kategori berhasil dihapus!');
    }
}
