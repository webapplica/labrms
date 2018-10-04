<?php

namespace App\Commands\Software\Type;

use App\Models\Software\Type;
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
			'type' => $request->type,

		]);
	}


}