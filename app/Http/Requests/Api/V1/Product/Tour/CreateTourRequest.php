<?php

namespace App\Http\Requests\Api\V1\Product\Tour;

use Illuminate\Foundation\Http\FormRequest;

class CreateTourRequest extends FormRequest
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
            'idr_price'             => 'required|numeric|max:100000000',
            'max_person'            => 'required|numeric|min:1',

            'id_description'        => 'required|string',
            'en_description'        => 'required|string',

            'locations'             => 'array',
            'locations.*'           => 'numeric|distinct|exists:locations,id'
        ];
    }
}
