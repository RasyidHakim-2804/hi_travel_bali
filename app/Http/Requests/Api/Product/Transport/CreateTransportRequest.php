<?php

namespace App\Http\Requests\Api\Product\Transport;

use Illuminate\Foundation\Http\FormRequest;

class CreateTransportRequest extends FormRequest
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
            'name'                  => 'required|string|max:255',
            'idr_price'             => 'required|numeric|max:100000000|min:0',
            'price_per_km'          => 'required|numeric|min:0|max:100000000',
            'max_person'            => 'required|numeric|min:1',
            'image'                 => 'required|image|max:2048',

            'facilities'            => 'array',
            'facilities.*'          => 'numeric|distinct|exists:facilities,id'
        ];
    }
}
