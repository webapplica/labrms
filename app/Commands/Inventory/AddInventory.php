<?php

namespace App\Commands\Inventory;

use App\Models\Unit;
use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Models\Inventory\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Inventory\Inventory;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket\Type as TicketType;

class AddInventory
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle(Inventory $inventory, Ticket $ticket)
	{
		$quantity = $this->request->quantity;
		$details = $this->request->details;
		$brand = $this->request->brand;
		$model = $this->request->model;

		$type_id = Type::findOrFail($this->request->type)->id;
		$unit = Unit::findOrFail($this->request->unit)->name;
		$user = Auth::user();

        DB::beginTransaction();

		$inventory = Inventory::firstOrCreate([
			'itemtype_id' => $type_id, 
			'brand' => $brand, 
			'model' => $model, 
			'unit_name' => $unit, 
			
		], [ 
			'code' => $inventory->generateCode(), 
			'user_id'=> $user->id,
			'details' => $details, 
		]);

		$log = Log::find($inventory->id);
		$sum = (isset($log) ? $log->sum('quantity') : 0 ) + $quantity;
		$details =  'Count for inventory ' . $inventory->brand . ' - ' . $inventory->model . ' has been updated with ' . $quantity . ' ' . $unit . '.';

		Log::create([
			'inventory_id' => $inventory->id,
			'quantity' => $quantity,
			'remaining_balance' => $sum, 
			'details' => $details,
			'user_id' => $user->id,
		]);
		
        Ticket::create([
            'title' => 'Inventory quantity updated',
            'details' => $details,
            'author' => $user->firstname_first,
            'type_id' => TicketType::firstOrCreate([ 'name' => 'Action'])->id,
            'user_id' => $user->id,
            'staff_id' => $user->id,
            'status' => $ticket->getClosedStatus(),
        ]);

        DB::commit();
		
	}
}