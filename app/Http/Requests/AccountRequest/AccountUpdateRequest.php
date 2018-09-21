<?php

namespace App\Http\Requests\AccountRequest;

use App\Models\User;
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
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = User::findOrFail($this->account);

        return [
            'username' => 'required_with:password|min:4|max:20|unique:users,username,' . $user->username . ',username',
            'firstname' => 'required|between:2,100|string',
            'middlename' => 'min:2|max:50|string',
            'lastname' => 'required|min:2|max:50|string',
            'contactnumber' => 'required|size:11|string',
            'email' => 'required|email'
        ];
    }

    public function validationData()
    {
        if (method_exists($this->route(), 'parameters')) {
            $this->request->add($this->route()->parameters('id'));
            $this->query->add($this->route()->parameters('id'));

            return array_merge($this->route()->parameters(), $this->all());
        }

        return $this->all();
    }
}
