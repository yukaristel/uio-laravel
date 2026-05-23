<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJurnalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tgl_transaksi'        => 'required|date',
            'rekening_debet'       => 'required|exists:chart_of_accounts,kode_akun',
            'rekening_kredit'      => 'required|exists:chart_of_accounts,kode_akun|different:rekening_debet',
            'keterangan_transaksi' => 'required|string|max:255',
            'jumlah'               => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'tgl_transaksi.required'        => 'Tanggal wajib diisi',
            'rekening_debet.required'        => 'Rekening debet wajib dipilih',
            'rekening_debet.exists'          => 'Rekening debet tidak valid',
            'rekening_kredit.required'       => 'Rekening kredit wajib dipilih',
            'rekening_kredit.exists'         => 'Rekening kredit tidak valid',
            'rekening_kredit.different'      => 'Rekening debet dan kredit tidak boleh sama',
            'keterangan_transaksi.required'  => 'Keterangan wajib diisi',
            'jumlah.required'                => 'Jumlah wajib diisi',
            'jumlah.min'                     => 'Jumlah minimal Rp 1',
        ];
    }
}
