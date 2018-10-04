<?php

namespace App\Commands\Activity;

use App\Models\Software;
use Illuminate\Http\Request;

class UnassignRoom
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
		$rooms = $this->request->get('rooms');

		$software = Software::findOrFail($this->id);
		$software->rooms()->syncWithoutDetaching($rooms);
	}
}
