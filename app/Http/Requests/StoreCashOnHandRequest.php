<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashOnHandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jumlah_100000' => 'nullable|integer|min:0',
            'jumlah_50000'  => 'nullable|integer|min:0',
            'jumlah_20000'  => 'nullable|integer|min:0',
            'jumlah_10000'  => 'nullable|integer|min:0',
            'jumlah_5000'   => 'nullable|integer|min:0',
            'jumlah_2000'   => 'nullable|integer|min:0',
            'jumlah_1000'   => 'nullable|integer|min:0',
            'jumlah_500'    => 'nullable|integer|min:0',
            'jumlah_200'    => 'nullable|integer|min:0',
            'jumlah_100'    => 'nullable|integer|min:0',
            'nominal_lain'  => 'nullable|numeric|min:0',
            'keterangan'    => 'nullable|string|max:255',
            'konfirmasi'    => 'nullable|boolean',
        ];
    }
}
