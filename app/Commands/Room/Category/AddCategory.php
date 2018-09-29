<?php

namespace App\Commands\Room\Category;

use Illuminate\Http\Request;
use App\Models\Room\Category;

class AddCategory
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		Category::create($this->request->toArray());
	}
}
		