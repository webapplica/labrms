<?php

namespace App\Commands\Workstation\Software;

use Carbon\Carbon;
use App\Models\Ticket\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Models\Software\License;
use App\Models\Software\Software;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Workstation\Workstation;

class InstallSoftware
{
	protected $request;
	protected $id;

	public function __construct(Request $request, $id)
	{
		$this->request = $request;
		$this->id = $id;
	}

	public function handle(Ticket $ticket)
	{
        $software = $this->request->software;
        $license = $this->request->license;
        $workstation = Workstation::findOrFail($this->id);
        $software = Software::findOrFail($software);
		$currentDate = Carbon::now()->toFormattedDateString();
		$currentAuthenticatedUser = Auth::user()->firstname_first;

        // initialize transaction
        DB::beginTransaction();
        
		$licenseFields = [];
        
        // checks if the license is inserted in the form
        // if inserted, create a new record with the licesne key
		if(isset($license)) {
			$license = License::firstOrCreate([ 'key' => $license ], [
				'software_id' => $software->id,
				'usage' => 1
			]);

			$licenseFields = [
				'license_id' => $license->id
			];
		}

        // attach the selected software to the workstation using the 
        // software id and workstation id along with the other information
        // such as license if its initialized
		$software->workstations()->attach($this->id, $licenseFields);

        // create a record of software installation and assign it to details variable
		$details = "Software $software->name installed on $workstation->name on $currentDate by $currentAuthenticatedUser. ";

        // create a ticket to record the software installation in the workstation
        // use the details created above this comment
		$ticket = Ticket::create([
			'title' => 'Software Installation',
			'details' => $details,
			'type_id' => Type::firstOrCreate([ 'name' => 'Action' ])->id,
			'staff_id' => Auth::user()->id,
			'user_id' => Auth::user()->id,
			'parent_id' => null,
			'main_id' => null,
			'status' => $ticket->getClosedStatus(),
			'author' => Auth::user()->firstname_first,
		]);

		// link the ticket to the workstation
		$ticket->workstation()->attach($workstation->id);

        // finish the transaction
        // execute the whole query and insert the 
        // record in the database
		DB::commit();
	}
}