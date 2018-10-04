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

        DB::beginTransaction();
        
        // find the record of item in database
        $item = Item::findOrFail($this->id);

        // create a new ticket for item 
        $ticket = Ticket::create([
            'title' => 'Activity added on an item',
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