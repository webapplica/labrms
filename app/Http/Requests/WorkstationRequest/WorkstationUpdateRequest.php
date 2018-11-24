<?php

namespace App\Http\Requests\WorkstationRequest;

use App\Models\Workstation\Workstation;
use Illuminate\Foundation\Http\FormRequest;

class WorkstationUpdateRequest extends FormRequest
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
        $workstation = Workstation::findOrFail($this->workstation);
        $uniqueOnWorkstationTable = implode('|', [
            'unique:workstations,systemunit_id,' . $workstation->systemunit_id . ',systemunit_id',
            'unique:workstations,monitor_id,' . $workstation->monitor_id . ',monitor_id',
            'unique:workstations,mouse_id,' . $workstation->mouse_id . ',mouse_id',
            'unique:workstations,keyboard_id,' . $workstation->keyboard_id . ',keyboard_id',
            'unique:workstations,avr_id,' . $workstation->avr_id . ',avr_id',
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
