<?php

namespace App\Http\Controllers;

use App\Models\TopupGame;
use App\Models\ChartOfAccount;
use App\Http\Requests\StoreTopupGameRequest;
use App\Services\TopupGameService;

class TopupGameController extends Controller
{
    public function __construct(
        private TopupGameService $topupGameService
    ) {}

    public function index()
    {
        $topupList = TopupGame::with('user')
                               ->latest('created_at')
                               ->paginate(15);
        return view('topup-game.index', compact('topupList'));
    }

    public function create()
    {
        $kasAkun = ChartOfAccount::where('kode_akun', 'like', '1.1.%')
                                  ->where('lev4', '>', 0)
                                  ->where('status', 'Aktif')
                                  ->orderBy('kode_akun')
                                  ->get();

        $gameList = TopupGame::select('nama_game')
                              ->distinct()
                              ->pluck('nama_game')
                              ->merge(['Free Fire', 'Mobile Legends'])
                              ->unique()
                              ->values();

        return view('topup-game.create', compact('kasAkun', 'gameList'));
    }

    public function store(StoreTopupGameRequest $request)
    {
        try {
            $topup = $this->topupGameService->createTopup($request->validated());
            return redirect()->route('topup-game.show', $topup)
                             ->with('success', 'Top-up berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal catat top-up: ' . $e->getMessage());
        }
    }

    public function show(TopupGame $topupGame)
    {
        $topupGame->load('user', 'rekeningBeli', 'rekeningBayar');
        return view('topup-game.show', compact('topupGame'));
    }
}
