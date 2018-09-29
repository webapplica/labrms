<?php

namespace App\Commands\Room\Category;

use Illuminate\Http\Request;
use App\Models\Room\Category;

class UpdateCategory
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
		$category = Category::findOrFail($this->id);
		$category->update($this->request->toArray());
	}
}
		