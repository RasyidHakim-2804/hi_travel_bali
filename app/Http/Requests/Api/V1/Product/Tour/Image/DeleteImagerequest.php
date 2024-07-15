<?php

namespace App\Http\Requests\Api\V1\Product\Tour\Image;

use Illuminate\Foundation\Http\FormRequest;

class DeleteImagerequest extends FormRequest
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
            'image_id' => [
                'required',
                'exists:product_id,id'
            ]
        ];
    }
}
