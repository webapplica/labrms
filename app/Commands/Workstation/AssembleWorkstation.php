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

class AssembleWorkstation
{

	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
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
		$license_key = $request->license_key;
		
		// check if the room exists and find the room with the 
		// corresponding name
		$room = Room::name($request->location)->first();

		// generate a code for the workstation name
		// use a custom package designed specifically to
		// generate a code
		$code = Code::make([
			config('app.workstation_id'),
			isset($room->name) ? $room->name : 'TMP',
			Workstation::count() + 1,
		], Code::DASH_SEPARATOR);

		// use transaction in order to change the record properly
		DB::beginTransaction();

		// create a record of workstation and store in variable workstation
		// use the variable code and items. find the specific item for the 
		// specific row and return the id for the said item
		$workstation = Workstation::create([
			'oskey' => $license_key,
			'systemunit_id' => $systemunit,
			'monitor_id' => $monitor,
			'avr_id' => $avr,
			'keyboard_id' => $keyboard,
			'mouse_id' => null,
			'name' => $code
		]);

		$details = "Workstation $workstation->name assembled on $currentDate by $currentAuthenticatedUser. ";

		// create a ticket to record the assembly in the workstation
		$ticket = Ticket::create([
			'title' => 'Item Profiling',
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

		// list all the items the workstation has
		$items = Item::find([
			$systemunit, $monitor, $keyboard, $avr
		]);

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
}