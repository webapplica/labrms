<?php

namespace App\Http\Requests\RoomRequest;

use Illuminate\Foundation\Http\FormRequest;

class RoomStoreRequest extends FormRequest
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
            'name' => 'required|min:4|max:100|unique:rooms,name' ,
            'description' => 'min:4',
            'categories' => 'exists:room_categories,id'
        ];
    }
}
