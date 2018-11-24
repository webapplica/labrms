<?php

namespace App\Commands\AcademicYear;

use Carbon\Carbon;
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
		$request = $this->request;
		$startOfTheYear = Carbon::parse($request->startOfYear);
		$endOfTheYear = Carbon::parse($request->endOfYear);
		$name = "{$startOfTheYear->format('Y')}-{$endOfTheYear->format('Y')}";

        AcademicYear::create([
			'start' => $startOfTheYear,
			'end' => $endOfTheYear,
			'name' => $name
		]);
	}
}
		