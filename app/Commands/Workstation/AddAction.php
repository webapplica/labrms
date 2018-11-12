<?php

namespace App\Commands\Workstation;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Workstation\Workstation;
use App\Models\Ticket\Type as TicketType;

class AddAction
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
		
		// assign global request to a local request variable
		// for handling easily
		$request = $this->request;
		$maintenance = $request->maintenance;

		// use transaction in order to change the record properly
		DB::beginTransaction();

		// use the variable code and items. find the specific item for the 
		// specific row and return the id for the said item
		$workstation = Workstation::findOrFail($this->id);

		// checks if the value for the status passed is different
		// from the value fetched from database
		if($workstation->isUnderMaintenance() != $maintenance) {
			$maintenanceStatus = false;

			// if the status is initialized, updates the 
			// value of the status in the database
			if(isset($maintenance)) {
				$maintenanceStatus = true;
			}

			$workstation->maintenance($maintenanceStatus);
		}

		// create a ticket to record the action in the workstation
		$ticket = Ticket::create([
			'title' => $request->subject,
			'details' => $request->details,
			'type_id' => TicketType::firstOrCreate([ 'name' => 'Action' ])->id,
			'staff_id' => Auth::user()->id,
			'user_id' => Auth::user()->id,
			'parent_id' => null,
			'main_id' => null,
			'status' => $ticket->getClosedStatus(),
			'author' => Auth::user()->firstname_first,
		]);

		$ticket->workstation()->attach($workstation->id);
		
		// check if the parts is greater than zero then
		// attach the ticket to the list of item part of workstation
		if (count($workstation->parts()->pluck('id')->toArray()) > 0) {
			$ticket->item()->attach(
				$workstation->parts()->pluck('id')->toArray()
			);
		}

		// end the transaction, commit the query
		// all the records will be added to the database
		DB::commit();
	}
}