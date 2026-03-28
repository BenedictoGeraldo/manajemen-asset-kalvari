<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePeminjamanRequest extends FormRequest
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
            'tanggal_pengajuan' => 'required|date',
            'tanggal_rencana_kembali' => 'nullable|date|after_or_equal:tanggal_pengajuan',
            'nama_peminjam' => 'required|string|max:150',
            'kontak_peminjam' => 'nullable|string|max:100',
            'unit_peminjam' => 'nullable|string|max:150',
            'status' => 'required|in:draft,diajukan,dibatalkan',
            'keperluan' => 'nullable|string',
            'catatan' => 'nullable|string',

            'items' => 'required|array|min:1',
            'items.*.data_aset_kolektif_id' => 'required|exists:data_aset_kolektif,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.catatan_item' => 'nullable|string',
        ];
    }
}
