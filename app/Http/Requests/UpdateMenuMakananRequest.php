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
        $menuId = $this->route('menu_makanan')?->id;

        return [
            'kode_menu'         => 'required|string|max:20|unique:menu_makanan,kode_menu,' . $menuId,
            'nama_menu'         => 'required|string|max:100',
            'kategori_id'       => 'required|exists:kategori_menu,id',
            'harga_modal'       => 'nullable|numeric|min:0',
            'harga_jual'        => 'required|numeric|min:0',
            'margin_keuntungan' => 'nullable|numeric',
            'status'            => 'required|in:tersedia,habis,tidak_tersedia',
            'foto_menu'         => 'nullable|image|max:2048',

            'resep'                 => 'nullable|array',
            'resep.*.bahan_id'      => 'required_with:resep|exists:bahan_baku,id',
            'resep.*.jumlah_bahan'  => 'required_with:resep|numeric|min:0.01',
            'resep.*.satuan'        => 'required_with:resep|in:kg,gram,liter,ml,pcs,sachet',

            'harga_grosir'                 => 'nullable|array',
            'harga_grosir.*.min_qty'       => 'required_with:harga_grosir|integer|min:2',
            'harga_grosir.*.harga_total'   => 'required_with:harga_grosir|numeric|min:1',
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

            'resep.*.bahan_id.required_with'     => 'Bahan wajib dipilih',
            'resep.*.bahan_id.exists'            => 'Bahan tidak valid',
            'resep.*.jumlah_bahan.required_with' => 'Jumlah bahan wajib diisi (min 0,01 — boleh desimal, mis. 0.5)',
            'resep.*.jumlah_bahan.numeric'       => 'Jumlah bahan harus angka',
            'resep.*.jumlah_bahan.min'           => 'Jumlah bahan minimal 0,01',
            'resep.*.satuan.required_with'       => 'Satuan wajib dipilih',
            'resep.*.satuan.in'                  => 'Satuan tidak valid',

            'harga_grosir.*.min_qty.required_with'     => 'Qty minimum wajib diisi',
            'harga_grosir.*.min_qty.integer'           => 'Qty minimum harus bilangan bulat',
            'harga_grosir.*.min_qty.min'               => 'Qty minimum minimal 2 (qty 1 pakai harga normal)',
            'harga_grosir.*.harga_total.required_with' => 'Harga bundle wajib diisi',
            'harga_grosir.*.harga_total.numeric'       => 'Harga bundle harus angka',
            'harga_grosir.*.harga_total.min'           => 'Harga bundle minimal 1',
        ];
    }
}
