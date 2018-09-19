<?php

namespace App\Http\Requests\AccountRequest;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
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
        $user = User::find($request->id);
        return [
            'id' => 'required|integer|exists:users,id',
            'username' => 'required_with:password|min:4|max:20|unique:users,username,' . $user->username . ',username',
            'firstname' => 'required|between:2,100|string',
            'middlename' => 'min:2|max:50|string',
            'lastname' => 'required|min:2|max:50|string',
            'contact_number' => 'required|size:11|string',
            'email' => 'required|email'
        ];
    }
}
