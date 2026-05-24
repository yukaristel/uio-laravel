<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTopupGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_game'     => 'required|string|max:50',
            'nama_item'     => 'required|string|max:100',
            'jumlah_item'   => 'required|numeric|min:0.01',
            'harga_beli'    => 'required|numeric|min:0',
            'harga_jual'    => 'required|numeric|min:0',
            'rekening_beli' => 'required|exists:chart_of_accounts,kode_akun',
            'rekening_bayar'=> 'required|exists:chart_of_accounts,kode_akun',
            'id_pelanggan'  => 'nullable|string|max:100',
            'keterangan'    => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_game.required'     => 'Nama game wajib dipilih',
            'nama_item.required'     => 'Item wajib diisi',
            'jumlah_item.required'   => 'Jumlah item wajib diisi',
            'harga_beli.required'    => 'Harga beli wajib diisi',
            'harga_jual.required'    => 'Harga jual wajib diisi',
            'rekening_beli.required' => 'Rekening beli wajib dipilih',
            'rekening_bayar.required'=> 'Rekening bayar wajib dipilih',
        ];
    }
}
