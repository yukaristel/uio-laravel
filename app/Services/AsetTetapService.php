<?php

namespace App\Services;

use App\Models\AsetTetap;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;

class AsetTetapService
{
    private array $akunMap = [
        'Peralatan Dapur' => [
            'aset'        => '1.3.01.01',
            'akumulasi'   => '1.3.02.01',
            'beban_susut' => '5.2.04.01',
        ],
        'Furniture' => [
            'aset'        => '1.3.01.02',
            'akumulasi'   => '1.3.02.02',
            'beban_susut' => '5.2.04.02',
        ],
        'Elektronik' => [
            'aset'        => '1.3.01.03',
            'akumulasi'   => '1.3.02.03',
            'beban_susut' => '5.2.04.03',
        ],
        'Lainnya' => [
            'aset'        => '1.3.01.99',
            'akumulasi'   => '1.3.02.99',
            'beban_susut' => '5.2.04.99',
        ],
    ];

    private array $rekeningKasMap = [
        'tunai'    => '1.1.01.01',
        'transfer' => '1.1.02.01',
        'kredit'   => '2.1.01.01',
    ];

    public function beliAset(array $data): AsetTetap
    {
        return DB::transaction(function () use ($data) {
            $aset      = AsetTetap::create($data);
            $akun      = $this->akunMap[$data['jenis']] ?? $this->akunMap['Lainnya'];
            $kredit    = $this->rekeningKasMap[$data['metode_beli']] ?? '1.1.01.01';
            $nilaiTotal = $aset->unit * $aset->harsat;

            Transaksi::create([
                'tgl_transaksi'        => $aset->tgl_beli,
                'rekening_debet'       => $akun['aset'],
                'rekening_kredit'      => $kredit,
                'keterangan_transaksi' => 'Pembelian aset: ' . $aset->nama_barang,
                'jumlah'               => (int) $nilaiTotal,
                'id_user'              => auth()->id(),
            ]);

            return $aset;
        });
    }

    public function catatPenyusutan(AsetTetap $aset, ?string $tanggal = null): void
    {
        if (!$aset->umur_ekonomis || $aset->umur_ekonomis <= 0) return;

        $nilaiTotal    = $aset->unit * $aset->harsat;
        $bebanPerBulan = $nilaiTotal / $aset->umur_ekonomis;
        $beban         = min($bebanPerBulan, $aset->nilai_buku);

        if ($beban <= 0) return;

        DB::transaction(function () use ($aset, $beban, $tanggal) {
            $akun = $this->akunMap[$aset->jenis] ?? $this->akunMap['Lainnya'];

            $aset->increment('akumulasi_penyusutan', $beban);

            Transaksi::create([
                'tgl_transaksi'        => $tanggal ?? now()->toDateString(),
                'rekening_debet'       => $akun['beban_susut'],
                'rekening_kredit'      => $akun['akumulasi'],
                'keterangan_transaksi' => 'Penyusutan: ' . $aset->nama_barang,
                'jumlah'               => (int) round($beban),
                'id_user'              => auth()->id(),
            ]);
        });
    }

    public function penyusutanBulananSemua(?string $tanggal = null): void
    {
        AsetTetap::whereIn('status', ['Baik', 'Maintenance'])
            ->whereNotNull('umur_ekonomis')
            ->where('umur_ekonomis', '>', 0)
            ->each(fn($aset) => $this->catatPenyusutan($aset, $tanggal));
    }

    public function lepasAset(AsetTetap $aset, string $statusBaru, float $hargaJual = 0, ?string $tanggal = null): void
    {
        DB::transaction(function () use ($aset, $statusBaru, $hargaJual, $tanggal) {
            $akun      = $this->akunMap[$aset->jenis] ?? $this->akunMap['Lainnya'];
            $akumulasi = $aset->akumulasi_penyusutan;
            $nilaiBuku = $aset->nilai_buku;
            $tgl       = $tanggal ?? now()->toDateString();
            $userId    = auth()->id();

            if ($statusBaru === 'Dijual') {
                $selisih = $hargaJual - $nilaiBuku;

                Transaksi::create([
                    'tgl_transaksi'        => $tgl,
                    'rekening_debet'       => $akun['akumulasi'],
                    'rekening_kredit'      => $akun['aset'],
                    'keterangan_transaksi' => 'Pelepasan aset (dijual): ' . $aset->nama_barang,
                    'jumlah'               => (int) $akumulasi,
                    'id_user'              => $userId,
                ]);

                if ($hargaJual > 0) {
                    Transaksi::create([
                        'tgl_transaksi'        => $tgl,
                        'rekening_debet'       => '1.1.01.01',
                        'rekening_kredit'      => $akun['aset'],
                        'keterangan_transaksi' => 'Penerimaan penjualan aset: ' . $aset->nama_barang,
                        'jumlah'               => (int) $hargaJual,
                        'id_user'              => $userId,
                    ]);
                }

                if ($selisih > 0) {
                    Transaksi::create([
                        'tgl_transaksi'        => $tgl,
                        'rekening_debet'       => $akun['aset'],
                        'rekening_kredit'      => '4.1.02.01',
                        'keterangan_transaksi' => 'Keuntungan penjualan aset: ' . $aset->nama_barang,
                        'jumlah'               => (int) $selisih,
                        'id_user'              => $userId,
                    ]);
                } elseif ($selisih < 0) {
                    Transaksi::create([
                        'tgl_transaksi'        => $tgl,
                        'rekening_debet'       => '5.3.02.01',
                        'rekening_kredit'      => $akun['aset'],
                        'keterangan_transaksi' => 'Kerugian penjualan aset: ' . $aset->nama_barang,
                        'jumlah'               => (int) abs($selisih),
                        'id_user'              => $userId,
                    ]);
                }

            } elseif ($statusBaru === 'Hapus') {
                Transaksi::create([
                    'tgl_transaksi'        => $tgl,
                    'rekening_debet'       => $akun['akumulasi'],
                    'rekening_kredit'      => $akun['aset'],
                    'keterangan_transaksi' => 'Penghapusan aset: ' . $aset->nama_barang,
                    'jumlah'               => (int) $akumulasi,
                    'id_user'              => $userId,
                ]);

                if ($nilaiBuku > 0) {
                    Transaksi::create([
                        'tgl_transaksi'        => $tgl,
                        'rekening_debet'       => '5.3.02.02',
                        'rekening_kredit'      => $akun['aset'],
                        'keterangan_transaksi' => 'Kerugian penghapusan aset: ' . $aset->nama_barang,
                        'jumlah'               => (int) $nilaiBuku,
                        'id_user'              => $userId,
                    ]);
                }
            }

            $aset->update(['status' => $statusBaru]);
        });
    }
}
