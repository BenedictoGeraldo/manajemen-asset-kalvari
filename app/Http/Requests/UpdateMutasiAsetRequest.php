<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMutasiAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'data_aset_kolektif_id' => ['required', 'exists:data_aset_kolektif,id'],
            'tanggal_mutasi' => ['required', 'date', 'date_format:Y-m-d'],
            'lokasi_baru_id' => ['required', 'exists:master_lokasi,id'],
            'nama_pengaju' => ['nullable', 'string', 'max:255'],
            'unit_pengaju' => ['nullable', 'string', 'max:255'],
            'alasan' => ['nullable', 'string'],
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'data_aset_kolektif_id.required' => 'Aset harus dipilih',
            'data_aset_kolektif_id.exists' => 'Aset tidak ditemukan',
            'tanggal_mutasi.required' => 'Tanggal mutasi harus diisi',
            'tanggal_mutasi.date' => 'Format tanggal tidak valid',
            'lokasi_baru_id.required' => 'Lokasi baru harus dipilih',
            'lokasi_baru_id.exists' => 'Lokasi tidak ditemukan',
        ];
    }
}
