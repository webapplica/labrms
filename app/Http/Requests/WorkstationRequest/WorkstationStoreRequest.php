<?php

namespace App\Http\Requests\WorkstationRequest;

use Illuminate\Foundation\Http\FormRequest;

class WorkstationStoreRequest extends FormRequest
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
        $uniqueOnWorkstationTable = implode('|', [
            'unique:workstations,systemunit_id',
            'unique:workstations,monitor_id',
            'unique:workstations,mouse_id',
            'unique:workstations,keyboard_id',
            'unique:workstations,avr_id',
        ]);

        return [
            'license_key' => 'nullable|max:30',
            'system_unit' => 'required|exists:items,id|' . $uniqueOnWorkstationTable,
            'monitor' => 'nullable|exists:items,id|' . $uniqueOnWorkstationTable,
            'avr' => 'nullable|exists:items,id|' . $uniqueOnWorkstationTable,
            'keyboard' => 'nullable|exists:items,id|' . $uniqueOnWorkstationTable,
            'ip_address' => 'nullable|max:256',
        ];
    }
}
