<?php

namespace App\Http\Requests\AccountRequest;

use Illuminate\Foundation\Http\FormRequest;

class AccountStoreRequest extends FormRequest
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
            'username' => 'required_with:password|min:4|max:20|unique:users,username',
            'firstname' => 'required|between:2,100|string',
            'middlename' => 'min:2|max:50|string',
            'lastname' => 'required|min:2|max:50|string',
            'contactnumber' => 'required|size:11|string',
            'email' => 'required|email'
        ];
    }
}
