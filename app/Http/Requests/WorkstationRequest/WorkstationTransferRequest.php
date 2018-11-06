<?php

namespace App\Http\Requests\WorkstationRequest;

use Illuminate\Foundation\Http\FormRequest;

class WorkstationTransferRequest extends FormRequest
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
            'room' => 'required|exists:rooms,id',
        ];
    }
}
