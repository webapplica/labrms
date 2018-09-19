<?php

namespace App\Http\Requests\AccountRequest;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'integer|exists:users,id|required',
            'current_password'=>'required|min:8|max:50',
            'new_password'=> [
                'required',
                'min:8',
                'max:50',
                Rule::notIn([ $request->current_password ]),
            ]
        ];
    }
}
