<?php

namespace App\Commands\Inventory\Profiling;

use Carbon\Carbon;
use App\Models\Item\Item;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket\Type as TicketType;

class AddActivity
{
    protected $request;
    protected $id;

	public function __construct(Request $request, $id)
	{
        $this->request = $request;
        $this->id = $id;
	}

	public function handle(Item $item, Ticket $ticket)
	{  
        $request = $this->request;
		$maintenance = $request->maintenance;

        DB::beginTransaction();
        
        // find the record of item in database
        $item = Item::findOrFail($this->id);

		// checks if the value for the status passed is different
		// from the value fetched from database
		if($item->isUnderMaintenance() != $maintenance) {
			$maintenanceStatus = false;

			// if the status is initialized, updates the 
			// value of the status in the database
			if(isset($maintenance)) {
				$maintenanceStatus = true;
			}

			$item->maintenance($maintenanceStatus);
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

        // link the ticket to the item
        $ticket->item()->attach($item->id);
		
		DB::commit();
   
	}
}