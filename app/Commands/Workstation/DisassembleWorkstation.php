<?php

namespace App\Commands\Workstation;

use Carbon\Carbon;
use App\Models\Item\Item;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Workstation\Workstation;
use App\Models\Ticket\Type as TicketType;
use App\Http\Modules\Generator\ListGenerator;

class DisassembleWorkstation
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
		$currentDate = Carbon::now()->toFormattedDateString();
		$currentAuthenticatedUser = Auth::user()->firstname_first;
		$workstation = Workstation::findOrFail($this->id);

		// use transaction in order to change the record properly
		DB::beginTransaction();

		$items = ListGenerator::makeArray(
			$workstation->systemunit,
			$workstation->monitor,
			$workstation->mouse,
			$workstation->keyboard,
			$workstation->avr
		)->unique();

		// for each item, create an item ticket to record assembly on the said item
		// and linked the ticket to the items
		foreach($items as $item)
		{
			$details = "Item $item->local_id unassigned from $workstation->name on $currentDate by $currentAuthenticatedUser. ";
			
			// create a new ticket for each item on workstation
			// sets the ticket =to closed
			$ticket = Ticket::create([
				'title' => 'Item unassigned from workstation',
				'details' => $details,
				'type_id' => TicketType::firstOrCreate([ 'name' => 'Action' ])->id,
				'staff_id' => Auth::user()->id,
				'user_id' => Auth::user()->id,
				'parent_id' => null,
				'main_id' => null,
				'status' => $ticket->getClosedStatus(),
				'author' => Auth::user()->firstname_first,
			]);
			
			// linked the ticket to the item
			$ticket->item()->attach($item->id);
		}

		// delete the workstation along with the connected items
		$workstation->delete();

		// end the transaction, commit the query
		// all the records will be added to the database
		DB::commit();
	}
}