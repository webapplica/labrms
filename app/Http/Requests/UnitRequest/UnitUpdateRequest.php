<?php

namespace App\Http\Requests\UnitRequest;

use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;

class UnitUpdateRequest extends FormRequest
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
        $unit = Unit::findOrFail($this->unit);
        return [
            'name' => 'required|unique:units,name,' . $unit->name . ',name',
            'description' => 'max:50',
            'abbreviation' => 'max:10'
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
