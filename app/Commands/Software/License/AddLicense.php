<?php

namespace App\Commands\Software\License;

use Illuminate\Http\Request;
use App\Models\Software\License;
use App\Models\Software\Software;
use Illuminate\Support\Facades\DB;

class AddLicense
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
        DB::beginTransaction();

		$license = License::create([
            'key' => $this->request->license,
            'software_id' => $this->request->id,
            'usage' => 0
        ]);

        DB::commit();
	}
}