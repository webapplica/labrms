<?php

namespace App\Http\Requests\TicketRequest;

use Illuminate\Foundation\Http\FormRequest;

class TicketStoreRequest extends FormRequest
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
            'subject' => 'required|max:256|string',
            'details' => 'required|max:256|string',
            'type' => 'required|exists:ticket_types,name',
            'author' => 'max:100'
        ];
    }
}
