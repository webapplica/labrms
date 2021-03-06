<?php

namespace App\Commands\Software\Room;

use App\Models\Software;
use Illuminate\Http\Request;

class UnassignSoftware
{
	protected $request;
	protected $id;
	protected $room_id;

	public function __construct($request, $id, $room_id) 
	{
		$this->request = $request;
		$this->id = $id;
		$this->room_id = $room_id;
	}

	public function handle()
	{
		Software::findOrFail($this->id)->rooms()->detach($this->room_id);
	}
}
