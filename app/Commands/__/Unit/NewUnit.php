<?php

namespace App\Commands\Unit;

use App\Models\Unit;
use Illuminate\Http\Request;

class NewUnit
{
	protected $request;

	public function __construct($request) 
	{
		$this->request = $request;
	}

	public function handle()
	{
		$request = $this->request;

        Unit::create([
        	'name' => $request->name,
        	'description' => $request->description,
        	'abbreviation' => $request->abbreviation,
        ]);
	}
}