<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBahanBakuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'kode_bahan'           => 'required|string|max:20|unique:bahan_baku',
            'nama_bahan'           => 'required|string|max:100',
            'satuan'               => 'required|in:kg,gram,liter,ml,pcs,sachet',
            'stok_tersedia'        => 'required|numeric|min:0',
            'stok_minimum'         => 'required|numeric|min:0',
            'harga_beli_per_satuan'=> 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'kode_bahan.required'            => 'Kode bahan wajib diisi',
            'kode_bahan.unique'              => 'Kode bahan sudah terdaftar',
            'nama_bahan.required'            => 'Nama bahan wajib diisi',
            'satuan.required'                => 'Satuan wajib dipilih',
            'stok_tersedia.required'         => 'Stok tersedia wajib diisi',
            'stok_minimum.required'          => 'Stok minimum wajib diisi',
            'harga_beli_per_satuan.required' => 'Harga beli wajib diisi',
        ];
    }
}
