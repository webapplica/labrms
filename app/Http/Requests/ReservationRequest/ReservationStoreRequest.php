<?php

namespace App\Http\Requests\ReservationRequest;

use Illuminate\Foundation\Http\FormRequest;

class ReservationStoreRequest extends FormRequest
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
            'items' => 'required',
            'location' => 'required|exists:rooms,id',
            'time_start' => 'required|date',
            'return_time' => 'required|date',
            'purpose' => 'required',
            'faculty' => 'nullable|exists:faculties,id'
        ];
    }
}
