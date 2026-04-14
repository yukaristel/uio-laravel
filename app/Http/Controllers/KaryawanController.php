<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawanList = User::latest()->paginate(15);
        return view('karyawan.index', compact('karyawanList'));
    }

    public function create()
    {
        return view('karyawan.create');
    }

    public function store(StoreKaryawanRequest $request)
    {
        User::create([
            'username'     => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
        ]);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil ditambahkan!');
    }

    public function edit(User $karyawan)
    {
        return view('karyawan.edit', compact('karyawan'));
    }

    public function update(UpdateKaryawanRequest $request, User $karyawan)
    {
        $data = [
            'username'     => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'role'         => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $karyawan->update($data);

        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil diubah!');
    }

    public function destroy(User $karyawan)
    {
        $karyawan->delete();
        return redirect()->route('karyawan.index')
                         ->with('success', 'Karyawan berhasil dihapus!');
    }
}
