<?php

namespace App\Commands\Faculty;

use App\Models\Faculty;
use Illuminate\Http\Request;

class UpdateFaculty
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
		Faculty::findOrFail($this->id)->update($this->request->toArray());
	}
}
		