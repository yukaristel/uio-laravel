<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username'     => 'admin',
            'nama_lengkap' => 'Administrator',
            'email'        => 'admin@uio.com',
            'password'     => Hash::make('12345'),
            'role'         => 'admin',
        ]);

        User::create([
            'username'     => 'karyawan',
            'nama_lengkap' => 'Karyawan Satu',
            'email'        => 'karyawan@uio.com',
            'password'     => Hash::make('12345'),
            'role'         => 'karyawan',
        ]);

        User::create([
            'username'     => 'kasir',
            'nama_lengkap' => 'Kasir Satu',
            'email'        => 'kasir@uio.com',
            'password'     => Hash::make('12345'),
            'role'         => 'kasir',
        ]);
    }
}
