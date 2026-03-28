<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompletePemeliharaanRequest extends FormRequest
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
            'tanggal_selesai' => 'required|date',
            'tindakan' => 'required|string',
            'realisasi_biaya' => 'required|numeric|min:0',
            'kondisi_sesudah_id' => 'required|exists:master_kondisi,id',
            'catatan' => 'nullable|string',
        ];
    }
}
