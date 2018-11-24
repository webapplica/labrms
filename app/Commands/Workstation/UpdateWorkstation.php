<?php

namespace App\Commands\Workstation;

use Carbon\Carbon;
use App\Models\Room\Room;
use App\Models\Item\Item;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use App\Http\Modules\Generator\Code;
use Illuminate\Support\Facades\Auth;
use App\Models\Workstation\Workstation;
use App\Models\Ticket\Type as TicketType;
use App\Http\Modules\Generator\ListGenerator;

class UpdateWorkstation
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

		// assign the values from the request class into the 
		// specific variables
		$systemunit = $request->system_unit;
		$monitor = $request->monitor;
		$avr = $request->avr;
		$keyboard = $request->keyboard;
		$mouse = $request->mouse;
		$ip_address = $request->ip_address;
		$license_key = $request->license_key;

		// use transaction in order to change the record properly
		DB::beginTransaction();

		// create a record of workstation and store in variable workstation
		// use the variable code and items. find the specific item for the 
		// specific row and return the id for the said item
		$workstation = Workstation::findOrFail($this->id);
		$workstation->update([
			'oskey' => $license_key,
			'systemunit_id' => $systemunit,
			'monitor_id' => $monitor,
			'avr_id' => $avr,
			'keyboard_id' => $keyboard,
			'ip_address' => $ip_address,
			'mouse_id' => null,
		]);

		$details = "Workstation $workstation->name parts updated on $currentDate by $currentAuthenticatedUser. ";

		// create a ticket to record the update of item in the workstation
		$ticket = Ticket::create([
			'title' => 'Workstation Part Update',
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
		$ticket->workstation()->attach($workstation->id);

		// filters item not in the workstation items id which is
		// system unit, monitor, avr, keyboard, and mouse
		// and returns them as value here
		$items = $this->filtersNotInArray(ListGenerator::makeArray(
			$systemunit, $monitor, $keyboard, $avr
		)->unique(), $workstation);

		// list all the items the workstation has
		// and fetch them all from the database
		$items = Item::findOrFail($items);
		
		// for each item, create an item ticket to record assembly on the said item
		// and linked the ticket to the items
		foreach($items as $item)
		{
			$details = "Item $item->local_id assigned to $workstation->name on $currentDate by $currentAuthenticatedUser. ";
			
			// create a new ticket for each item on workstation
			// sets the ticket =to closed
			$ticket = Ticket::create([
				'title' => 'Item assigned to a workstation',
				'details' => 'Item ' . $item->local_id . ' assigned to ' . $workstation->name . ' on ' . Carbon::now()->toFormattedDateString() . ' by '. Auth::user()->firstname_first . '. ',
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

		// end the transaction, commit the query
		// all the records will be added to the database
		DB::commit();
	}

	/**
	 * Checks if the first parameter is in the list given
	 * in the second parameter and returns unique items
	 *
	 * @param array $items
	 * @param array $workstations
	 * @return array
	 */
	public function filtersNotInArray($items, $workstation)
	{

		return array_filter($items, function ($item) use ($workstation) {

			// checks if the item is not in the array given
			// returns true if the item is unique and not in the 
			// workstation list
			return ! in_array( $item, ListGenerator::makeArray(
				$workstation->systemunit_id, $workstation->monitor_id, $workstation->keyboard_id, $workstation->mouse_id, $workstation->avr_id
			)->unique()) ? true : false;
		});
	}
}