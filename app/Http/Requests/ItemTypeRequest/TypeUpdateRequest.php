<?php

namespace App\Http\Requests\ItemTypeRequest;

use App\Models\Item\Type;
use Illuminate\Foundation\Http\FormRequest;

class TypeUpdateRequest extends FormRequest
{
    protected $type;
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
        $args = $this->route()->parameters('id');
        $type = Type::findOrFail( $args['type'] ?? null );
        return [
            'name' => 'required|min:2|max:50|unique:item_types,name,'. $type->name .',name',
            'description' => 'min:5|max:450'
        ];
    }
}
