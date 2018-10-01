<?php

namespace App\Commands\Inventory;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Models\Inventory\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket\Type as TicketType;

class ReleaseInventory
{
    protected $request;
    protected $id;

	public function __construct(Request $request, $id)
	{
        $this->request = $request;
        $this->id = $id;
	}

	public function handle(Inventory $inventory, Ticket $ticket)
	{
        DB::beginTransaction();

        $id = $this->request->id;
        $details =  $this->request->details;
        $quantity = $this->request->quantity;
        
		$inventory = Inventory::findOrFail($id);
        $log = Log::filterByInventory($inventory->id)->get();
        $log_total_count = (count($log) > 0) ? $log->sum('quantity') : 0;

		Log::create([
			'inventory_id' => $inventory->id,
			'quantity' => $quantity * -1,
			'remaining_balance' => $log_total_count - $quantity, 
			'details' => $details,
			'user_id' => Auth::user()->id,
        ]);
        
        Ticket::create([
            'title' => 'Inventory released',
            'details' => $details,
            'author' => Auth::user()->firstname_first,
            'type_id' => TicketType::firstOrCreate([ 'name' => 'Action'])->id,
            'user_id' => Auth::user()->id,
            'staff_id' => Auth::user()->id,
            'status' => $ticket->getClosedStatus(),
		]);

        DB::commit();
		
	}
}