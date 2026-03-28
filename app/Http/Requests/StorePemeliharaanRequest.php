<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePemeliharaanRequest extends FormRequest
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
            'data_aset_kolektif_id' => 'required|exists:data_aset_kolektif,id',
            'tanggal_pengajuan' => 'required|date',
            'tanggal_rencana' => 'nullable|date|after_or_equal:tanggal_pengajuan',
            'jenis_pemeliharaan' => 'required|in:rutin,perbaikan,darurat',
            'prioritas' => 'required|in:rendah,sedang,tinggi',
            'status' => 'required|in:draft,diajukan,dibatalkan',
            'vendor_nama' => 'nullable|string|max:150',
            'vendor_kontak' => 'nullable|string|max:100',
            'estimasi_biaya' => 'nullable|numeric|min:0',
            'realisasi_biaya' => 'nullable|numeric|min:0',
            'keluhan' => 'nullable|string',
            'tindakan' => 'nullable|string',
            'catatan' => 'nullable|string',
            'kondisi_sesudah_id' => 'nullable|exists:master_kondisi,id',
        ];
    }
}
