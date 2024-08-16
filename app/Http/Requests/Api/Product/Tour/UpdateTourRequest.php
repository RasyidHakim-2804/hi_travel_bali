<?php

namespace App\Http\Requests\Api\Product\Tour;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTourRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
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
            'is_active'             => 'required|boolean',

            'id_description'        => 'required|string',
            'en_description'        => 'required|string',

            'locations'             => 'array',
            'locations.*'           => 'numeric|distinct|exists:locations,id'
        ];
    }
}
