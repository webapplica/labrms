<?php

namespace App\Http\Requests\FacultyRequest;

use Illuminate\Foundation\Http\FormRequest;

class FacultyUpdateRequest extends FormRequest
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
            'firstname' => 'required|between:2,100|string',
            'middlename' => 'min:2|max:50|string',
            'lastname' => 'required|min:2|max:50|string',
            'contact_number' => 'size:11|string',
            'email' => 'email',
            'suffix' => 'max:3',
            'title' => 'nullable|max:30',
        ];
    }
}
