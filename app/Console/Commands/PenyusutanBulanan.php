<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AsetTetapService;

class PenyusutanBulanan extends Command
{
    protected $signature   = 'aset:penyusutan-bulanan {--tanggal= : Tanggal penyusutan (Y-m-d), default hari ini}';
    protected $description = 'Catat penyusutan bulanan untuk semua aset aktif';

    public function handle(AsetTetapService $service): void
    {
        $tanggal = $this->option('tanggal') ?? now()->toDateString();

        $this->info("Menjalankan penyusutan bulanan untuk tanggal: {$tanggal}");

        $service->penyusutanBulananSemua($tanggal);

        $this->info('Penyusutan bulanan selesai dicatat.');
    }
}
