<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMenuMakananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_menu'         => 'required|string|max:20|unique:menu_makanan,kode_menu,' . $this->menuMakanan->id,
            'nama_menu'         => 'required|string|max:100',
            'kategori_id'       => 'required|exists:kategori_menu,id',
            'harga_modal'       => 'nullable|numeric|min:0',
            'harga_jual'        => 'required|numeric|min:0',
            'margin_keuntungan' => 'nullable|numeric',
            'status'            => 'required|in:tersedia,habis,tidak_tersedia',
            'foto_menu'         => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_menu.required'   => 'Kode menu wajib diisi',
            'kode_menu.unique'     => 'Kode menu sudah terdaftar',
            'nama_menu.required'   => 'Nama menu wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists'   => 'Kategori tidak valid',
            'harga_jual.required'  => 'Harga jual wajib diisi',
            'status.required'      => 'Status wajib dipilih',
        ];
    }
}
