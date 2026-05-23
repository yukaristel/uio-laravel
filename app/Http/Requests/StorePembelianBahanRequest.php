<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianBahanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bahan_id'          => 'required|exists:bahan_baku,id',
            'jumlah_beli'       => 'required|numeric|min:0.01',
            'harga_beli_satuan' => 'required|numeric|min:1',
            'supplier'          => 'nullable|string|max:100',
            'tanggal_beli'      => 'required|date',
            'rekening_bayar'    => 'required|exists:chart_of_accounts,kode_akun',
        ];
    }

    public function messages(): array
    {
        return [
            'bahan_id.required'          => 'Bahan baku wajib dipilih',
            'bahan_id.exists'            => 'Bahan baku tidak valid',
            'jumlah_beli.required'       => 'Jumlah beli wajib diisi',
            'jumlah_beli.min'            => 'Jumlah beli minimal 0.01',
            'harga_beli_satuan.required' => 'Harga beli wajib diisi',
            'harga_beli_satuan.min'      => 'Harga beli minimal Rp 1',
            'tanggal_beli.required'      => 'Tanggal beli wajib diisi',
            'rekening_bayar.required'    => 'Rekening pembayaran wajib dipilih',
            'rekening_bayar.exists'      => 'Rekening tidak valid',
        ];
    }
}
