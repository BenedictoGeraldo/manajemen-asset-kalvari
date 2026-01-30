<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataAsetRequest extends FormRequest
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
            'nama_aset' => 'required|string|max:200',
            'kategori_id' => 'required|exists:master_kategori,id',
            'deskripsi_aset' => 'nullable|string',
            'ukuran' => 'nullable|string|max:100',
            'deskripsi_ukuran_bentuk' => 'nullable|string',
            'lokasi_id' => 'required|exists:master_lokasi,id',
            'kegunaan' => 'required|string',
            'keterangan_kegunaan' => 'nullable|string',
            'jumlah_barang' => 'required|integer|min:1',
            'tipe_grup' => 'required|in:individual,set,grup',
            'keterangan_tipe_grup' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'keterangan_budget' => 'nullable|string',
            'pengelola_id' => 'required|exists:master_pengelola,id',
            'tahun_pengadaan' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'nilai_pengadaan_total' => 'nullable|numeric|min:0',
            'nilai_pengadaan_per_unit' => 'nullable|numeric|min:0',
            'kondisi_id' => 'required|exists:master_kondisi,id',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_aset' => 'nama aset',
            'kategori_id' => 'kategori',
            'deskripsi_aset' => 'deskripsi aset',
            'lokasi_id' => 'lokasi',
            'kegunaan' => 'kegunaan',
            'jumlah_barang' => 'jumlah barang',
            'tipe_grup' => 'tipe grup',
            'pengelola_id' => 'pengelola',
            'tahun_pengadaan' => 'tahun pengadaan',
            'nilai_pengadaan_total' => 'nilai pengadaan total',
            'nilai_pengadaan_per_unit' => 'nilai pengadaan per unit',
            'kondisi_id' => 'kondisi',
        ];
    }
}
