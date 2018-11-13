<?php

namespace App\Commands\AcademicYear;

use Illuminate\Http\Request;
use App\Models\Scheduling\AcademicYear;

class AddAcademicYear
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
        AcademicYear::create($this->request->toArray());
	}
}
		