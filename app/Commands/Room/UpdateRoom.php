<?php

namespace App\Commands\Room;

use App\Models\Room\Room;
use Illuminate\Http\Request;

class UpdateRoom
{
    protected $request;
    protected $id;

	public function __construct(Request $request, $id)
	{
        $this->request = $request;
        $this->id = $id;
	}

	public function handle()
	{
		$room = Room::findOrFail($this->id);
		$room->update($this->request->toArray());
		$room->categories()->sync($this->request->category);
	}
}
		