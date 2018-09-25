<?php

namespace App\Commands\Item\Type;

use App\Models\Item\Type;
use Illuminate\Http\Request;

class NewType
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
			'name' => $request->name,
			'description' => $request->description,
			'category' => $request->category,

		]);
	}
}