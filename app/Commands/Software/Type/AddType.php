<?php

namespace App\Commands\Software\Type;

use App\Models\Software\Type;
use Illuminate\Http\Request;

class AddType
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle(Type $type)
	{
		$request = $this->request;
		$type->create([
			'type' => $request->type,

		]);
	}
}