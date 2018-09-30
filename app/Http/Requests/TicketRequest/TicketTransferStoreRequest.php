<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class TicketTransferStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(Auth::user()->isStaff()) {
            return true;
        }

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
            'staff' => 'required|exists:users,id',
            'subject' => 'required|max:100|string',
            'details' => 'required|max:256|string',
        ];
    }
}
