<?php

namespace App\Commands\Reservation\Purpose;

use App\Models\Reservation\Purpose;
use Illuminate\Http\Request;

class UpdatePurpose
{
	protected $Purpose;
	protected $request;

	public function __construct(Request $request, $id)
	{
		$this->request = $request;
		$this->id = $id;
	}

	public function handle()
	{
		$request = $this->request;
		$purpose = Purpose::findOrFail($this->id);

		$purpose->update([
			'title' => $request->title,
			'description' => $request->description,
		]);
	}


}