<?php

namespace App\Commands\Activity;

use App\Models\Activity;
use Illuminate\Http\Request;

class NewActivity
{
	protected $request;

	public function __construct($request) 
	{
		$this->request = $request;
	}

	public function handle()
	{
		$request = $this->request;

        Activity::create([
        	'name' => $request->name,
        	'type' => $request->type,
        	'details' => $request->details,
        ]);
	}
}