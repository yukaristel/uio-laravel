<?php

namespace App\Services;

use App\Models\TopupGame;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class TopupGameService
{
    private string $rekeningPendapatan = '4.1.03.01';
    private string $rekeningHPP        = '5.1.02.01';

    public function createTopup(array $data): TopupGame
    {
        return DB::transaction(function () use ($data) {

            // 1. Catat topup
            $topup = TopupGame::create([
                'no_transaksi'  => $this->generateNoTransaksi(),
                'nama_game'     => $data['nama_game'],
                'nama_item'     => $data['nama_item'],
                'jumlah_item'   => $data['jumlah_item'],
                'harga_beli'    => $data['harga_beli'],
                'harga_jual'    => $data['harga_jual'],
                'rekening_beli' => $data['rekening_beli'],
                'rekening_bayar'=> $data['rekening_bayar'],
                'id_pelanggan'  => $data['id_pelanggan'] ?? null,
                'keterangan'    => $data['keterangan'] ?? null,
                'user_id'       => auth()->id(),
            ]);

            // 2. Jurnal HPP (modal keluar)
            // Debet: HPP Top-Up → Kredit: Kas yang dipakai beli
            Transaksi::create([
                'tgl_transaksi'        => now()->toDateString(),
                'rekening_debet'       => $this->rekeningHPP,
                'rekening_kredit'      => $data['rekening_beli'],
                'keterangan_transaksi' => 'HPP Top-Up ' . $data['nama_game'] . ' - ' . $topup->no_transaksi,
                'jumlah'               => (int) $data['harga_beli'],
                'id_user'              => auth()->id(),
            ]);

            // 3. Jurnal Pendapatan (uang masuk)
            // Debet: Kas yang diterima → Kredit: Pendapatan Top-Up
            Transaksi::create([
                'tgl_transaksi'        => now()->toDateString(),
                'rekening_debet'       => $data['rekening_bayar'],
                'rekening_kredit'      => $this->rekeningPendapatan,
                'keterangan_transaksi' => 'Penjualan Top-Up ' . $data['nama_game'] . ' - ' . $topup->no_transaksi,
                'jumlah'               => (int) $data['harga_jual'],
                'id_user'              => auth()->id(),
            ]);

            return $topup;
        });
    }

    public function generateNoTransaksi(): string
    {
        $tanggal = date('Ymd');
        $lastNum = TopupGame::whereDate('created_at', today())->count();
        $newNum  = $lastNum + 1;
        return 'TPU' . $tanggal . str_pad($newNum, 3, '0', STR_PAD_LEFT);
    }
}
