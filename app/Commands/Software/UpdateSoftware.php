<?php

namespace App\Commands\Software;

use Illuminate\Http\Request;
use App\Models\Software\Software;

class UpdateSoftware
{
	protected $request;
	protected $id;

	public function __construct($request, $id) 
	{
		$this->request = $request;
		$this->id = $id;
	}

	public function handle()
	{
		$name = $this->request->name;
		$softwareType = $this->request->input('software_type');
		$licenseType = $this->request->input('license_type');
		$company = $this->request->company;
		$minimumRequirements = $this->request->input('minimum_requirements');
		$recommendedRequirements = $this->request->input('recommended_requirements');

		Software::findOrFail($this->id)->update([
			'name' => $name,
			'type' => $softwareType,
			'license_type' => $licenseType,
			'company' => $company,
			'minimum_requirements' => $minimumRequirements,
			'recommended_requirements' => $recommendedRequirements,
		]);
	}
}