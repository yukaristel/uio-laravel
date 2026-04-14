<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStockOpnameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_opname'  => 'required|date',
            'bahan_id'        => 'required|exists:bahan_baku,id',
            'stok_fisik'      => 'required|numeric|min:0',
            'jenis_selisih'   => 'nullable|in:hilang,rusak,expired,tumpah,salah_hitung,lainnya',
            'keterangan'      => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_opname.required' => 'Tanggal opname wajib diisi',
            'bahan_id.required'       => 'Bahan baku wajib dipilih',
            'bahan_id.exists'         => 'Bahan baku tidak valid',
            'stok_fisik.required'     => 'Stok fisik wajib diisi',
            'stok_fisik.min'          => 'Stok fisik tidak boleh negatif',
        ];
    }
}
