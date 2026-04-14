<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChartOfAccount;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [1,1,0,0,'1.1.00.00','Kas dan Bank','Debet',1],
            [1,1,1,0,'1.1.01.00','Kas','Debet',1],
            [1,1,1,1,'1.1.01.01','Kas Tunai','Debet',1],
            [1,1,1,2,'1.1.01.02','Kas QRIS','Debet',1],
            [1,1,1,3,'1.1.01.03','Kas GoPay','Debet',1],
            [1,1,1,4,'1.1.01.04','Kas Grab','Debet',1],
            [1,1,1,99,'1.1.01.99','Kas Lain-lain','Debet',1],
            [1,1,2,0,'1.1.02.00','Bank','Debet',1],
            [1,1,2,1,'1.1.02.01','Bank Mandiri','Debet',1],
            [1,1,2,99,'1.1.02.99','Bank Lain-lain','Debet',1],
            [1,2,0,0,'1.2.00.00','Persediaan','Debet',1],
            [1,2,1,0,'1.2.01.00','Persediaan Bahan Baku','Debet',1],
            [1,2,1,99,'1.2.01.99','Persediaan Lain-lain','Debet',1],
            [1,3,0,0,'1.3.00.00','Aset Tetap','Debet',1],
            [1,3,1,0,'1.3.01.00','Peralatan','Debet',1],
            [1,3,1,1,'1.3.01.01','Peralatan Dapur','Debet',1],
            [1,3,1,2,'1.3.01.02','Furniture','Debet',1],
            [1,3,1,3,'1.3.01.03','Elektronik','Debet',1],
            [1,3,1,99,'1.3.01.99','Peralatan Lain-lain','Debet',1],
            [1,3,2,0,'1.3.02.00','Akumulasi Penyusutan','Kredit',1],
            [1,3,2,1,'1.3.02.01','Akumulasi Penyusutan Peralatan','Kredit',1],
            [1,3,2,2,'1.3.02.02','Akumulasi Penyusutan Furniture','Kredit',1],
            [1,3,2,3,'1.3.02.03','Akumulasi Penyusutan Elektronik','Kredit',1],
            [1,3,2,99,'1.3.02.99','Akumulasi Penyusutan Lain-lain','Kredit',1],
            [2,1,0,0,'2.1.00.00','Utang Lancar','Kredit',1],
            [2,1,1,0,'2.1.01.00','Utang Usaha','Kredit',1],
            [2,1,1,1,'2.1.01.01','Utang Supplier','Kredit',1],
            [2,1,1,99,'2.1.01.99','Utang Usaha Lain-lain','Kredit',1],
            [2,2,0,0,'2.2.00.00','Utang Jangka Panjang','Kredit',1],
            [2,2,1,0,'2.2.01.00','Pinjaman','Kredit',1],
            [2,2,1,1,'2.2.01.01','Pinjaman Pihak Ketiga','Kredit',1],
            [2,2,1,99,'2.2.01.99','Pinjaman Lain-lain','Kredit',1],
            [3,1,0,0,'3.1.00.00','Modal','Kredit',1],
            [3,1,1,0,'3.1.01.00','Modal Pemilik','Kredit',1],
            [3,1,1,1,'3.1.01.01','Modal Pemilik','Kredit',1],
            [3,2,0,0,'3.2.00.00','Laba Ditahan','Kredit',1],
            [3,2,1,0,'3.2.01.00','Laba Ditahan','Kredit',1],
            [4,1,0,0,'4.1.00.00','Pendapatan Usaha','Kredit',2],
            [4,1,1,0,'4.1.01.00','Pendapatan Penjualan','Kredit',2],
            [4,1,1,1,'4.1.01.01','Pendapatan Penjualan Makanan','Kredit',2],
            [4,1,1,99,'4.1.01.99','Pendapatan Penjualan Lain-lain','Kredit',2],
            [4,1,2,0,'4.1.02.00','Pendapatan Non-Usaha','Kredit',2],
            [4,1,2,1,'4.1.02.01','Keuntungan Penjualan Aset','Kredit',2],
            [5,1,0,0,'5.1.00.00','Harga Pokok Penjualan','Debet',2],
            [5,1,1,0,'5.1.01.00','HPP','Debet',2],
            [5,1,1,1,'5.1.01.01','HPP Bahan Baku','Debet',2],
            [5,1,1,99,'5.1.01.99','HPP Lain-lain','Debet',2],
            [5,2,0,0,'5.2.00.00','Beban Operasional','Debet',2],
            [5,2,1,0,'5.2.01.00','Beban Gaji','Debet',2],
            [5,2,1,1,'5.2.01.01','Beban Gaji','Debet',2],
            [5,2,2,0,'5.2.02.00','Beban Utilitas','Debet',2],
            [5,2,2,1,'5.2.02.01','Beban Listrik','Debet',2],
            [5,2,2,99,'5.2.02.99','Beban Utilitas Lain-lain','Debet',2],
            [5,2,3,0,'5.2.03.00','Beban Sewa','Debet',2],
            [5,2,3,1,'5.2.03.01','Beban Sewa Gedung','Debet',2],
            [5,2,3,99,'5.2.03.99','Beban Sewa Lain-lain','Debet',2],
            [5,2,4,0,'5.2.04.00','Beban Penyusutan','Debet',2],
            [5,2,4,1,'5.2.04.01','Beban Penyusutan Peralatan Dapur','Debet',2],
            [5,2,4,2,'5.2.04.02','Beban Penyusutan Furniture','Debet',2],
            [5,2,4,3,'5.2.04.03','Beban Penyusutan Elektronik','Debet',2],
            [5,2,4,99,'5.2.04.99','Beban Penyusutan Lain-lain','Debet',2],
            [5,2,99,0,'5.2.99.00','Beban Operasional Lain-lain','Debet',2],
            [5,2,99,99,'5.2.99.99','Beban Operasional Lain-lain','Debet',2],
            [5,3,0,0,'5.3.00.00','Beban Lain-lain','Debet',2],
            [5,3,1,0,'5.3.01.00','Beban Bunga','Debet',2],
            [5,3,1,1,'5.3.01.01','Beban Bunga Pinjaman','Debet',2],
            [5,3,2,0,'5.3.02.00','Kerugian Pelepasan Aset','Debet',2],
            [5,3,2,1,'5.3.02.01','Kerugian Penjualan Aset','Debet',2],
            [5,3,2,2,'5.3.02.02','Kerugian Penghapusan Aset','Debet',2],
            [5,3,99,0,'5.3.99.00','Beban Lain-lain','Debet',2],
            [5,3,99,99,'5.3.99.99','Beban Lain-lain','Debet',2],
        ];

        foreach ($accounts as $a) {
            ChartOfAccount::create([
                'lev1'         => $a[0],
                'lev2'         => $a[1],
                'lev3'         => $a[2],
                'lev4'         => $a[3],
                'kode_akun'    => $a[4],
                'nama_akun'    => $a[5],
                'jenis_mutasi' => $a[6],
                'posisi'       => $a[7],
                'status'       => 'Aktif',
            ]);
        }
    }
}
