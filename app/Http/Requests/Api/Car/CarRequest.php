<?php

namespace App\Http\Requests\Api\Car;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
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
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|max:100000000|min:0',
            'is_active'  => 'required|boolean',
            'image'      => 'required|image|max:2048|mimes:png,jpg,jpeg',
            'max_person' => 'required|numeric|min:1|max:100',

            'facilities'    => 'required|array',
            'facilities.*'  => 'numeric|distinct|exists:facilities,id'
        ];

        if (strtolower($this->method()) == 'put') {
            $rule['image'] = 'nullable|image|max:2048|mimes:png,jpg,jpeg';
        }

        return $rule;
    }
}
