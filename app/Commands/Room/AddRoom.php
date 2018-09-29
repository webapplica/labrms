<?php

namespace App\Commands\Room;

use App\Models\Room\Room;
use Illuminate\Http\Request;

class AddRoom
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		$room = Room::create($this->request->toArray());
		$room->categories()->sync($this->request->category);
	}
}
		