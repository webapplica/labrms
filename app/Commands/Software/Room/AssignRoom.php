<?php

namespace App\Commands\Software\Room;

use Illuminate\Http\Request;
use App\Models\Software\License;
use App\Models\Software\Software;
use Illuminate\Support\Facades\DB;

class AssignRoom
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
		Software::findOrFail($this->request->id)->rooms()->attach($this->request->room);
	}
}