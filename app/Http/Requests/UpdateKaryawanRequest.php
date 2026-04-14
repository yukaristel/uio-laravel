<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKaryawanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username'     => 'required|string|max:50|unique:users,username,' . $this->karyawan->id,
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'required|email|unique:users,email,' . $this->karyawan->id,
            'password'     => 'nullable|string|min:5|confirmed',
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
            'password.min'          => 'Password minimal 5 karakter',
            'password.confirmed'    => 'Konfirmasi password tidak cocok',
            'role.required'         => 'Role wajib dipilih',
        ];
    }
}
