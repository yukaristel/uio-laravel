<?php

namespace App\Http\Controllers;

use App\Models\MenuMakanan;
use App\Models\KategoriMenu;
use App\Models\BahanBaku;
use App\Models\ResepMenu;
use App\Http\Requests\StoreMenuMakananRequest;
use App\Http\Requests\UpdateMenuMakananRequest;
use Illuminate\Http\Request;

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
        $bahanList    = BahanBaku::orderBy('nama_bahan')->get();
        return view('menu-makanan.create', compact('kategoriList', 'bahanList'));
    }

    public function store(StoreMenuMakananRequest $request)
    {
        $menu = MenuMakanan::create($request->validated());
        $this->syncResep($menu, $request->input('resep', []));
        $this->syncHargaGrosir($menu, $request->input('harga_grosir', []));
        $this->recalculateHpp($menu);

        return redirect()->route('menu-makanan.index')
                         ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function show(MenuMakanan $menuMakanan)
    {
        $menuMakanan->load('kategori', 'reseps.bahanBaku');
        $bahanList = BahanBaku::orderBy('nama_bahan')->get();
        return view('menu-makanan.show', compact('menuMakanan', 'bahanList'));
    }

    public function edit(MenuMakanan $menuMakanan)
    {
        $kategoriList  = KategoriMenu::all();
        $bahanList     = BahanBaku::orderBy('nama_bahan')->get();
        $menuMakanan->load('reseps.bahanBaku', 'hargaGrosirs');
        return view('menu-makanan.edit', compact('menuMakanan', 'kategoriList', 'bahanList'));
    }

    public function update(UpdateMenuMakananRequest $request, MenuMakanan $menuMakanan)
    {
        $menuMakanan->update($request->validated());
        $this->syncResep($menuMakanan, $request->input('resep', []));
        $this->syncHargaGrosir($menuMakanan, $request->input('harga_grosir', []));
        $this->recalculateHpp($menuMakanan);

        return redirect()->route('menu-makanan.index')
                         ->with('success', 'Menu berhasil diubah!');
    }

    public function destroy(MenuMakanan $menuMakanan)
    {
        $menuMakanan->delete();
        return redirect()->route('menu-makanan.index')
                         ->with('success', 'Menu berhasil dihapus!');
    }

    public function destroyResep(MenuMakanan $menuMakanan, ResepMenu $resep)
    {
        abort_unless($resep->menu_id === $menuMakanan->id, 404);
        $resep->delete();
        $this->recalculateHpp($menuMakanan->fresh());

        return back()->with('success', 'Bahan resep berhasil dihapus.');
    }

    public function generateHpp()
    {
        $menus = MenuMakanan::with('reseps.bahanBaku')->get();

        foreach ($menus as $menu) {
            $this->recalculateHpp($menu);
        }

        return redirect()->route('menu-makanan.index')
                         ->with('success', 'HPP semua menu berhasil digenerate ulang!');
    }

    protected function syncResep(MenuMakanan $menu, array $reseps): void
    {
        $menu->reseps()->delete();

        $seen = [];
        foreach ($reseps as $row) {
            if (empty($row['bahan_id']) || empty($row['jumlah_bahan']) || empty($row['satuan'])) {
                continue;
            }
            $bahanId = (int) $row['bahan_id'];
            if (isset($seen[$bahanId])) {
                continue;
            }
            $seen[$bahanId] = true;

            $menu->reseps()->create([
                'bahan_id'     => $bahanId,
                'jumlah_bahan' => (float) $row['jumlah_bahan'],
                'satuan'       => $row['satuan'],
                'biaya_bahan'  => 0,
            ]);
        }
    }

    protected function syncHargaGrosir(MenuMakanan $menu, array $tiers): void
    {
        $menu->hargaGrosirs()->delete();

        $seen = [];
        foreach ($tiers as $row) {
            $minQty = (int) ($row['min_qty'] ?? 0);
            $harga  = (float) ($row['harga_total'] ?? 0);
            if ($minQty < 2 || $harga <= 0) {
                continue;
            }
            if (isset($seen[$minQty])) {
                continue;
            }
            $seen[$minQty] = true;

            $menu->hargaGrosirs()->create([
                'min_qty'     => $minQty,
                'harga_total' => $harga,
            ]);
        }
    }

    protected function recalculateHpp(MenuMakanan $menu): void
    {
        $menu->load('reseps.bahanBaku');
        $totalModal = 0;

        foreach ($menu->reseps as $resep) {
            $hargaBahan = $resep->bahanBaku->harga_beli_per_satuan ?? 0;
            $biayaBahan = (float) $resep->jumlah_bahan * (float) $hargaBahan;
            $resep->update(['biaya_bahan' => $biayaBahan]);
            $totalModal += $biayaBahan;
        }

        $menu->update([
            'harga_modal'       => $totalModal,
            'margin_keuntungan' => (float) $menu->harga_jual - $totalModal,
        ]);
    }
}
