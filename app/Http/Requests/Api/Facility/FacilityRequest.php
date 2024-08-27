<?php

namespace App\Http\Requests\Api\Facility;

use Illuminate\Foundation\Http\FormRequest;

class FacilityRequest extends FormRequest
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
            'id_name'   => 'required|string|max:255',
            'en_name'   => 'required|string|max:255',
            'image'     => 'required|image|max:2048|mimes:png,jpg'
        ];
        
        if (strtolower($this->method()) == 'put') {
            $rule['image'] = 'nullable|image|max:2048|mimes:png,jpg';
        }

        return $rule;
    }
}
