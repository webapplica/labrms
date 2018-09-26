<?php

namespace App\Commands\Activity;

use App\Models\Software;
use Illuminate\Http\Request;

class RemoveSoftware
{
	protected $request;

	public function __construct($request) 
	{
		$this->request = $request;
	}

	public function handle()
	{

	}
}