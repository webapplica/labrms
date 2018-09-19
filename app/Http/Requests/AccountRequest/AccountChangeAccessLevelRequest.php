<?php

namespace App\Http\Requests\AccountRequest;

use Illuminate\Foundation\Http\FormRequest;

class AccountChangeAccessLevelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->accesslevel != User::getAdminId()) {
            return false;
        }

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
            'id' => 'required|integer|exists:user,id',
            'new_access_level' => 'required|integer',
        ];
    }
}
