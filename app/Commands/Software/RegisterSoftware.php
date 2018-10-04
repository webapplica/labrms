<?php

namespace App\Commands\Software;

use App\Models\Software\Software;
use Illuminate\Http\Request;

class RegisterSoftware
{
	protected $request;

	public function __construct($request) 
	{
		$this->request = $request;
	}

	public function handle()
	{
		$name = $this->request->name;
		$softwareType = $this->request->input('software_type');
		$licenseType = $this->request->input('license_type');
		$company = $this->request->company;
		$minimumRequirements = $this->request->input('minimum_requirements');
		$recommendedRequirements = $this->request->input('recommended_requirements');

		Software::create([
			'name' => $name,
			'type' => $softwareType,
			'license_type' => $licenseType,
			'company' => $company,
			'minimum_requirements' => $minimumRequirements,
			'recommended_requirements' => $recommendedRequirements,
		]);
	}
}
