<?php

namespace App\Http\Requests\Api\Tour;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Language;
use Illuminate\Validation\Rule;

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
            'price'         => 'exclude_if:is_varied,true|required|numeric|max:100000000|min:0',
            'max_person'    => 'required|numeric|min:1',
            'is_active'     => 'required|boolean',

            'description'   => 'required|array',

            'locations'     => 'required|array',
            'locations.*'   => 'numeric|distinct|exists:locations,id',

            'is_varied'     => 'required|boolean',

            'variants'                => 'exclude_if:is_varied,false|required|array',
            'variants.*'              => 'exclude_if:is_varied,false|required|array',
            'variants.*.name'         => 'exclude_if:is_varied,false|required|string|max:50',
            'variants.*.price'        => 'exclude_if:is_varied,false|required|numeric|max:100000000|min:0',
            'variants.*.descriptions' => 'exclude_if:is_varied,false|required|array'
        ];

        foreach (Language::code() as $lang) {
            $rule['description.' . $lang]               = 'required|string|max:200';
            $rule['variants.*.descriptions.' . $lang]   = 'required|string|max:200';
        }

        if ($this->isMethod('PUT')) {

            $rule['variants.*.id'] = [
                'exclude_if:is_varied,false',
                'nullable',
                'distinct',
                Rule::exists('tour_variants', 'id')->where('tour_id', $this->route('tour')->id)
            ];
        }

        return $rule;
    }
}
