<?php

namespace App\Commands\Reservation\Purpose;

use App\Models\Reservation\Purpose;
use Illuminate\Http\Request;

class AddPurpose
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle(Purpose $purpose)
	{
		$request = $this->request;
		$purpose->create([
			'title' => $request->title,
			'description' => $request->description,

		]);
	}
}