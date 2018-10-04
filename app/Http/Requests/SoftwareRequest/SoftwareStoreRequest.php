<?php

namespace App\Http\Requests\SoftwareRequest;

use Illuminate\Foundation\Http\FormRequest;

class SoftwareStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            	'name' => 'required|min: 2|max: 100',
            	'software_type' => 'required|min: 2|max: 100',
            	'license_type' => 'required|min: 2|max: 100',
            	'company' => 'min: 2|max: 100',
            	'minimum_requirements' => 'min: 2|max: 100',
            	'recommended_requirements' => 'min: 2|max: 100'
        ];
    }
}
