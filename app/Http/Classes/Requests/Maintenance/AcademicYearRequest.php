<?php

namespace App\Http\Classes\Requests\Maintenance;

use Illuminate\Http\Request;

class AcademicYearRequest extends Request
{
	public function rules()
	{
		return [
	        'date_started' => 'required|date',
	        'date_ended' => 'required|date'
		];
	}
}