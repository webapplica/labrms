<?php

namespace App\Commands\Inventory\Profiling;

use Carbon\Carbon;
use App\Models\Room\Room;
use App\Models\Item\Item;
use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket\Type as TicketType;

class BatchProfiling
{
    protected $request;
    protected $id;

	public function __construct(Request $request, $id)
	{
        $this->request = $request;
        $this->id = $id;
	}

	public function handle(Ticket $ticket, Item $item)
	{  
		$receipt_id = $this->request->receipt_id;
		$inventory_id = $this->request->id;
		$property_number = $this->request->get('property-number');
		$local_property_number = $this->request->get('local-property-number');
		$serial_number = $this->request->get('serial-id');
		$date_received = $this->request->date_received;
		$room_id = $this->request->location;
		$quantity = 0;

		$ticket = new Ticket;
		$inventory = Inventory::findOrFail($inventory_id);
		$location = Room::findOrFail($room_id)->name;

		DB::beginTransaction();
		
		foreach($property_number as $key => $value) {

			// create a new record of item in database
			// stores the information in the items table

			$item = Item::create([
				'local_id' => isset($local_property_number[$key]) ? $local_property_number[$key] : $item->generateCode($inventory),
				'property_number' => $property_number[$key],
				'serial_number' => $serial_number[$key],
				'date_received' => $date_received,
				'inventory_id' => $inventory_id,
				'receipt_id' => $receipt_id,
				'profiled_by' => Auth::user()->firstname_first,
				'status' => $item->getWorkingStatus(),
				'location' => $location,
			]);
	
			// create a new ticket for item profiling
			// sets the ticket status to profiling
			$ticket = Ticket::create([
				'title' => 'Item Profiling',
				'details' => 'Item ' . $item->local_id . ' profiled on ' . Carbon::now()->toFormattedDateString() . ' by '. Auth::user()->firstname_first . '. ',
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
			$quantity++;
		}
		
		// add count to profiled items
		$inventory = $inventory->receipts()->findOrFail($receipt_id);
		$inventory->pivot->profiled_items = (isset($inventory->pivot->profiled_items) ? $inventory->pivot->profiled_items : 0) + $quantity;
		$inventory->pivot->save();


		DB::commit();
   
	}
}