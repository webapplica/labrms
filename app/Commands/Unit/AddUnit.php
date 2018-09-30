<?php

namespace App\Commands\Unit;

use App\Models\Unit;
use Illuminate\Http\Request;

class AddUnit
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		Unit::create($this->request->toArray());
	}
}
		