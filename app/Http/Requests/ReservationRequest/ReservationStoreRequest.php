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
            'location' => 'required|exists:rooms,id',
            'time_start' => 'required|date_format:H:iA',
            'return_time' => 'required|date_format:H:iA|after:time_start',
            'purpose' => 'required',
            'faculty' => 'nullable|exists:faculties,id'
        ];
    }
}
