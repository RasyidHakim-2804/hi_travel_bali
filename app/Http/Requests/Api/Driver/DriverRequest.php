<?php

namespace App\Http\Requests\Api\Driver;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DriverRequest extends FormRequest
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
            'name'      => 'required|string|max:100',
            'phone'     => 'required|string|max:20',
            'cars_id'   => 'required|array',
            'cars_id.*' => 'exists:cars,id',
            'email'     => [
                'required',
                'email'
            ]
        ];

        if (strtolower($this->method()) == 'put') {

            $rule['email'][] = Rule::unique('drivers')->ignore($this->route('driver')->id);
        
        } else {
            $rule['email'][] = 'unique:drivers,email';
        }
        

        return $rule;
    }
}
