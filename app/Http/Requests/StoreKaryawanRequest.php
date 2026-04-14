<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'     => 'required|string|max:50|unique:users',
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'required|email|unique:users',
            'password'     => 'required|string|min:5|confirmed',
            'role'         => 'required|in:admin,karyawan,kasir',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required'     => 'Username wajib diisi',
            'username.unique'       => 'Username sudah terdaftar',
            'nama_lengkap.required' => 'Nama lengkap wajib diisi',
            'email.required'        => 'Email wajib diisi',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.min'          => 'Password minimal 5 karakter',
            'password.confirmed'    => 'Konfirmasi password tidak cocok',
            'role.required'         => 'Role wajib dipilih',
        ];
    }
}
