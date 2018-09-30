<?php

namespace App\Commands\Item\Type;

use App\Models\Item\Type;
use Illuminate\Http\Request;

class UpdateType
{
	protected $type;
	protected $request;

	public function __construct(Request $request, $id)
	{
		$this->request = $request;
		$this->id = $id;
	}

	public function handle()
	{
		$request = $this->request;
		$type = Type::findOrFail($this->id);

		$type->update([
			'name' => $request->name,
			'description' => $request->description,
			'category' => $request->category,

		]);
	}


}