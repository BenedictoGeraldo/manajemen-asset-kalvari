<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianRequest extends FormRequest
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
            'tanggal_pembelian' => 'required|date',
            'vendor_nama' => 'required|string|max:150',
            'vendor_kontak' => 'nullable|string|max:100',
            'sumber_dana' => 'nullable|string|max:150',
            'status' => 'required|in:draft,diajukan,dibatalkan',
            'catatan' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.nama_item' => 'required|string|max:200',
            'items.*.kategori_id' => 'required|exists:master_kategori,id',
            'items.*.deskripsi' => 'nullable|string',
            'items.*.kegunaan' => 'required|string|max:200',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|numeric|min:0',
            'items.*.lokasi_id' => 'required|exists:master_lokasi,id',
            'items.*.kondisi_id' => 'required|exists:master_kondisi,id',
            'items.*.pengelola_id' => 'required|exists:master_pengelola,id',
            'items.*.tahun_pengadaan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'items.*.catatan' => 'nullable|string',
        ];
    }
}
