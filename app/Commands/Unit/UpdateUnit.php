<?php

namespace App\Commands\Unit;

use App\Models\Unit;
use Illuminate\Http\Request;

class UpdateUnit
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
		Unit::findOrFail($this->id)->update($this->request->toArray());
	}
}
		