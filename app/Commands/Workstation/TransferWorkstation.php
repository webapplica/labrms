<?php

namespace App\Commands\Workstation;

use Carbon\Carbon;
use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Workstation\Workstation;
use App\Models\Ticket\Type as TicketType;

class TransferWorkstation
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

		// use transaction in order to change the record properly
		DB::beginTransaction();

		// fetch a room by given name
		$room = Room::findOrFail($request->room);

		// use the variable code and items. find the specific item for the 
		// specific row and return the id for the said item
		$workstation = Workstation::findOrFail($this->id);
		$workstation->update([
			'name' => $workstation->generateName($room->name),
			'room_id' => $room->id 
		]);

		$details = "Workstation $workstation->name transferred to $room->name $currentDate by $currentAuthenticatedUser. ";

		// create a ticket to record the assembly in the workstation
		$ticket = Ticket::create([
			'title' => 'Workstation Transfer',
			'details' => $details,
			'type_id' => TicketType::firstOrCreate([ 'name' => 'Action' ])->id,
			'staff_id' => Auth::user()->id,
			'user_id' => Auth::user()->id,
			'parent_id' => null,
			'main_id' => null,
			'status' => $ticket->getClosedStatus(),
			'author' => Auth::user()->firstname_first,
		]);

		$ticket->workstation()->attach($workstation->id);

		// end the transaction, commit the query
		// all the records will be added to the database
		DB::commit();
	}
}