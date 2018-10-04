<?php

namespace App\Commands\Inventory\Profiling\Reservation;

use Carbon\Carbon;
use App\Models\Item\Item;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket\Type as TicketType;

class ToggleItem
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

        $reservationStatus = 'disabled';
        $boolReservationStatus = false;

        if($this->request->reservation) {
            $boolReservationStatus = true;
            $reservationStatus = 'enabled';
        }

        DB::beginTransaction();
        
        // find the record of item in database
        $item = Item::findOrFail($this->id);
        
        $item->update([
            'for_reservation' => $boolReservationStatus,
        ]);

        // create a new ticket for item 
        $ticket = Ticket::create([
            'title' => 'Item ' . $reservationStatus . ' for reservation',
            'details' => $this->request->details,
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
		
		DB::commit();
   
	}
}