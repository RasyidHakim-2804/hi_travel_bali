<?php

namespace App\Http\Requests\Api\V1\Product\Tour;

use Illuminate\Foundation\Http\FormRequest;

class UserListRequest extends FormRequest
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
            'location_id'       => 'required|exists:locations,id',
            'tourist_numbers'   => 'required|numeric|min:1'
        ];
    }
}
