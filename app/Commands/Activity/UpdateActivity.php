<?php

namespace App\Commands\Activity;

use App\Models\Activity;
use Illuminate\Http\Request;

class UpdateActivity
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
		$request = $this->request;

        Activity::findOrFail($this->id)->update([
        	'name' => $request->name,
        	'type' => $request->type,
        	'details' => $request->details,
        ]);
	}
}