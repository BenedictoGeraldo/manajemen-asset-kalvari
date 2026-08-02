<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectPembelianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'alasan_penolakan' => 'required|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'alasan_penolakan' => 'alasan penolakan',
        ];
    }
}
