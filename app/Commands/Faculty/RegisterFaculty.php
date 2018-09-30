<?php

namespace App\Commands\Faculty;

use App\Models\Faculty;
use Illuminate\Http\Request;

class RegisterFaculty
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
        Faculty::create($this->request->toArray());
	}
}
		