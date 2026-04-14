<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('aset:penyusutan-bulanan')
         ->monthlyOn(1, '00:05')
         ->appendOutputTo(storage_path('logs/penyusutan.log'));
