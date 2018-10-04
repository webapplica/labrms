<?php

namespace App\Commands\Software;

use Illuminate\Http\Request;
use App\Models\Software\Software;

class RemoveSoftware
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
		Software::findOrFail($this->id)->delete();
	}
}