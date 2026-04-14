<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransaksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanggal_transaksi'            => 'required|date',
            'metode_pembayaran'            => 'required|in:tunai,debit,qris,transfer',
            'uang_bayar'                   => 'required|numeric|min:0',
            'details'                      => 'required|array|min:1',
            'details.*.menu_id'            => 'required|exists:menu_makanan,id',
            'details.*.jumlah'             => 'required|integer|min:1',
            'details.*.harga_satuan'       => 'required|numeric|min:0',
            'details.*.harga_modal_satuan' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_transaksi.required'    => 'Tanggal transaksi wajib diisi',
            'metode_pembayaran.required'    => 'Metode pembayaran wajib dipilih',
            'uang_bayar.required'           => 'Uang bayar wajib diisi',
            'details.required'              => 'Item transaksi wajib diisi',
            'details.min'                   => 'Minimal 1 item transaksi',
            'details.*.menu_id.required'    => 'Menu wajib dipilih',
            'details.*.jumlah.required'     => 'Jumlah wajib diisi',
            'details.*.harga_satuan.required' => 'Harga satuan wajib diisi',
        ];
    }
}
