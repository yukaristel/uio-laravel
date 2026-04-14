<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAsetTetapRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_barang'    => 'required|string|max:200',
            'tgl_beli'       => 'required|date',
            'unit'           => 'required|integer|min:1',
            'harsat'         => 'required|numeric|min:0',
            'umur_ekonomis'  => 'nullable|integer|min:1',
            'jenis'          => 'required|in:Peralatan Dapur,Furniture,Elektronik,Lainnya',
            'metode_beli'    => 'required|in:tunai,transfer,kredit',
            'status'         => 'required|in:Baik,Rusak,Dijual,Hapus,Maintenance',
            'tgl_validasi'   => 'nullable|date',
            'keterangan'     => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama barang wajib diisi',
            'tgl_beli.required'    => 'Tanggal beli wajib diisi',
            'unit.required'        => 'Jumlah unit wajib diisi',
            'harsat.required'      => 'Harga satuan wajib diisi',
            'jenis.required'       => 'Jenis aset wajib dipilih',
            'metode_beli.required' => 'Metode beli wajib dipilih',
            'status.required'      => 'Status wajib dipilih',
        ];
    }
}
