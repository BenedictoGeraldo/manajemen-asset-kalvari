<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMutasiAsetRequest extends FormRequest
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
            'jenis_mutasi' => ['required', 'in:transfer_lokasi,perubahan_kondisi,write_off,penghapusan'],
            'status' => ['required', 'in:draft,diajukan,dibatalkan'],
            'lokasi_baru_id' => ['nullable', 'exists:master_lokasi,id'],
            'kondisi_id' => ['nullable', 'exists:master_kondisi,id'],
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
            'jenis_mutasi.required' => 'Jenis mutasi harus dipilih',
            'jenis_mutasi.in' => 'Jenis mutasi tidak valid',
            'status.required' => 'Status harus dipilih',
            'status.in' => 'Status tidak valid',
        ];
    }
}
