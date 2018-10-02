<?php

namespace App\Commands\Inventory\Profiling;

use Illuminate\Http\Request;

class BatchProfiling
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
        
	}
}