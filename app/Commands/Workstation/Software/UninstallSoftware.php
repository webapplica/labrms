<?php

namespace App\Commands\Workstation\Software;

use Carbon\Carbon;
use App\Models\Ticket\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Workstation\Workstation;

class UninstallSoftware
{
	protected $request;
	protected $workstation;
	protected $software;

	public function __construct(Request $request, $workstation, $software)
	{
		$this->request = $request;
		$this->workstation = $workstation;
		$this->software = $software;
	}

	public function handle(Ticket $ticket)
	{
		$request = $this->request;
        $workstation = Workstation::findOrFail($this->workstation);
        $software = $workstation->softwares()->findOrFail($request->software);
		$currentDate = Carbon::now()->toFormattedDateString();
		$currentAuthenticatedUser = Auth::user()->firstname_first;
		$remarks = $request->remarks;

        // initialize transaction
        DB::beginTransaction();
        
		$withAdditionalRemarks = '';
		
		// check if remarks field has content, add the content
		// to the details field along with the default lines
		if(isset($remarks) && strlen($remarks) > 0) {
			$withAdditionalRemarks = 'The user added a note as follows: ' . $remarks;
		}

        // create a record of software removal and assign it to details variable
		$details = "Software $software->name removed from workstation $workstation->name on $workstation->name on $currentDate by $currentAuthenticatedUser. " . $withAdditionalRemarks;

        // create a ticket to record the software removal in the workstation
        // use the details created above this comment
		$ticket = Ticket::create([
			'title' => 'Software Removal',
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

		// detach the software from the workstation if the software exists in
		// the record of workstations softwares
		$workstation->softwares()->detach($software->id);

        // finish the transaction
        // execute the whole query and insert the 
        // record in the database
		DB::commit();
	}
}