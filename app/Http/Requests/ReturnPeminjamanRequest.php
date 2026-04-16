<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReturnPeminjamanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tanggal_dikembalikan' => 'required|date',
            'catatan_pengembalian' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:transaksi_peminjaman_items,id',
            'items.*.kondisi_akhir_id' => 'nullable|exists:master_kondisi,id',
            'items.*.catatan_pengembalian' => 'nullable|string',
        ];
    }
}
