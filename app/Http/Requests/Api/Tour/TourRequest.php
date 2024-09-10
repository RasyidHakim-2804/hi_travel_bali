<?php

namespace App\Http\Requests\Api\Tour;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;

class TourRequest extends FormRequest
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
        $rule = [
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric|max:100000000',
            'max_person'    => 'required|numeric|min:1',
            'is_active'     => 'required|boolean',

            'description'   => 'required|array',
            'meta'          => 'required|array',

            'locations'     => 'array',
            'locations.*'   => 'numeric|distinct|exists:locations,id'
        ];

        foreach (Language::code() as $lang) {
            $rule['description.' . $lang]   = 'required|string|max:200';
            $rule['meta.' . $lang]          = 'required|string|max:200';
        }

        return $rule;
    }
}
