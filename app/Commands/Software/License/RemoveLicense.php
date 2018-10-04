<?php

namespace App\Commands\Software\License;

use Illuminate\Http\Request;
use App\Models\Software\License;

class RemoveLicense
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
		License::findOrFail($this->id)->delete();
	}
}