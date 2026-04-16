<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteMutasiAsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'catatan' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}
