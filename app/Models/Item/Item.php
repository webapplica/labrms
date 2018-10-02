<?php

namespace App\Models\Item;

// use DB;
// use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
	// use SoftDeletes;

	const WORKING_STATUS = 'working';
	
	protected $table = 'items';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $fillable = [
		'local_id', 'property_number', 'serial_number', 'location', 'date_received', 'status',
		'inventory_id', 'receipt_id', 'profiled_by', 'warranty', 'lent_at', 'lent_by', 'deployed_at',
		'deployed_by', 'for_reservation'
	];
	
	// public static $rules = array(
	// 	'University Property Number' => 'min:5|max:100|unique:items,local_id',
	// 	'Property Number' => 'min:5|max:100|unique:items,property_number',
	// 	'Serial Number' => 'required|min:5|max:100|unique:items,serial_number',
	// 	'Location' =>'required',
	// 	'Date Received' =>'required|date',
	// 	'Status' =>'required|min:5|max:50'

	// );
	
	// public static $updateRules = array(
	// 	'Property Number' => 'min:5|max:100',
	// 	'Serial Number' => 'min:5|max:100',
	// 	'Location' =>'',
	// 	'Date Received' =>'date',
	// 	'Status' =>'min:5|max:50'
	// );

	// public static $updateForReservationRules = array(
	// 	'id' => 'required|exists:items,id',
	// 	'checked' => 'required|boolean',
	// );

	// public function scopeEnabledReservation($query)
	// {
	// 	$query->where('for_reservation', '=', 1);
	// }

	// public function scopeDisabledReservation($query)
	// {
	// 	$query->where('for_reservation', '=', 0);
	// }

	// public function disableReservation()
	// {
	// 	$this->for_reservation = 0;
	// 	$this->save();

	// 	return $this;
	// }

	// public function enableReservation()
	// {
	// 	$this->for_reservation = 1;
	// 	$this->save();

	// 	return $this;
	// }

	public static $category = [
		'equipment',
		'fixtures',
		'furniture',
		'supplies'
	];

	protected $appends = [
		'parsed_date_received', 'parsed_date_profiled', 'reservation_status'
	];

	/**
	 * Returns the status if the item is available for reservation
	 *
	 * @return void
	 */
	public function getReservationStatusAttribute()
	{
		return ($this->for_reservation) ? "Yes" : "No";
	}

	/**
	 * Return human readable date where the item is profiled
	 *
	 * @return void
	 */
	public function getParsedDateReceivedAttribute()
	{
		return Carbon::parse($this->date_received)->toFormattedDateString();
	}

	/**
	 * Return human readable date format
	 *
	 * @return void
	 */
	public function getParsedDateProfiledAttribute()
	{
		return Carbon::parse($this->created_at)->toFormattedDateString();
	}

	/**
	 * Returns relationship to type table
	 *
	 * @return void
	 */
	// public function itemtype()
	// {
	// 	return $this->hasManyThrough('App\ItemType','App\Inventory','id','id');
	// }

	/*
	*
	*	Foreign key referencing inventory table
	*
	*/
	// public function inventory()
	// {
	// 	return $this->belongsTo('App\Inventory', 'inventory_id', 'id');
	// }

	/*
	*
	*	Foreign key referencing receipt table
	*
	*/
	// public function receipt()
	// {
	// 	return $this->belongsTo('App\Receipt','receipt_id','id');
	// }

	/*
	*
	*	Foreign key referencing room table
	*
	*/
	// public function room()
	// {
	// 	return $this->belongsTo('App\Room', 'location','id');
	// }


	/*
	*
	*	Foreign key referencing ticket table
	*
	*/
	// public function tickets()
	// {
	// 	return $this->belongsToMany('App\Ticket','item_ticket','item_id','ticket_id');
	// }

	// public function scopefindByLocation($query,$location)
	// {
	// 	return $query->where('location','=', $location);	
	// }

	// public function scopefindByLocalCode($query, $value)
	// {
	// 	return $query->where('local','=',$value);
	// }

	// public function scopeFindByStatus($query, $value)
	// {
	// 	return $query->where('status', '=', $value);
	// }

	// public function scopeFindByPropertyNumber($query,$propertynumber)
	// {
	// 	return $query->where('property_number', '=', $propertynumber);
	// }

	// public function scopeFindByLocalId($query,$local_id)
	// {
	// 	return $query->where('local_id','=',$local_id);
	// }

	/**
	 * Filters the current search result by id
	 *
	 * @param Builder $query
	 * @param int $id
	 * @return object
	 */
	public function scopeFilterByTypeId($query, int $id)
	{
		return $query->whereHas('inventory', function($query) use ($id) {
			$query->where('type_id', '=', $id);
		});
	}

	/*
	*
	*	Limit the scope by propertynumber
	*	usage: Item::propertyNumber($propertynumber)->get()
	*
	*/
	// public function scopePropertyNumber($query,$propertynumber)
	// {
	// 	return $query->where('property_number','=',$propertynumber);
	// }

	// public static function assignToRoom($item,$room)
	// {

	// 	$item = Item::find($item);

	// 	$staff = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
	// 	$staff_id = Auth::user()->id;
	// 	$details = "$item->property_number assigned to $room->name by $staff";
	// 	$ticket_id = null;
	// 	$status = 'Closed';
	// 	$item_id = $item->id;

	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Transfer'
	// 	]);

	// 	$title = 'Transfer';

	// 	/*
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	| 	Save
	// 	|	1 - If existing update
	// 	|	2 - If not create new record
	// 	|
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	*/			

	// 	$item->location = $room->id;
	// 	if($item->deployed_at == null)
	// 	{
	// 		$item->deployed_at = Carbon\Carbon::now();
	// 		$item->deployed_by = $staff;
	// 		$title = 'Deployment';
	// 	}

	// 	$item->save();

	// 	$ticket = new Ticket;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->parent_id = $ticket_id;
	// 	$ticket->status = $status;
	// 	$ticket->generate($item_id);


	// }

	/**
	 * Generate code based on the format given
	 *
	 * @param object $inventory
	 * @return void
	 */
	public function generateCode($inventory)
	{
		$type = $inventory->type_id;
		$local_constant_id = config('app.local.constant');
		$local_id_count = Item::filterByType($inventory->type_id)->count() + 1;

		return $local_constant_id . '-' . $type . '-' . $local_id_count;
	}

	public function getWorkingStatus()
	{
		return self::WORKING_STATUS;
	}

	/**
	*
	*	@param $propertynumber
	*	@param $serialnumber
	*	@param $location name
	*	@param $datereceived
	*	@param $inventory_id referencing inventory table
	*	@param $receipt_id referencing receipt table
	*
	*/
	// public function profile()
	// {

	// 	/**
	// 	*
	// 	*	Set local information
	// 	*
	// 	*/
	// 	$_org = config('app.local.constant');

	// 	$inventory = Inventory::find($this->inventory_id);

	// 	$itemtype = isset($inventory->itemtype->id) ? "-" . $inventory->itemtype->id : "";

	// 	$local =  Item::whereHas('inventory',function($query) use ($inventory){
	// 		$query->where('itemtype_id','=',$inventory->itemtype_id);
	// 	})->count() + 1;

	// 	//$this->local_id =  $_org . $itemtype . "-" . ($local + 1);

	// 	$this->status = 'working';
	// 	$this->profiled_by = Auth::user()->firstname . " " . Auth::user()->middlename . " " .Auth::user()->lastname;
	// 	$this->save();	

	// 	/*
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	| 	Create initial ticket
	// 	|
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	*/
	// 	Item::createProfilingTicket($this->id,$this->datereceived);
	   

	// 	/*
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	| 	Add 1 to profiled items count
	// 	|	Used to check if how many items are not yet profiled
	// 	|	Located in inventory table
	// 	|
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	*/
	//     Inventory::addProfiled($this->inventory_id, $this->receipt_id);

	// }

	/*
	*
	*	Create a profiling ticket
	*	Send item id from create record to this
	*
	*/
	// public static function createProfilingTicket($item_id,$datereceived)
	// {
	// 	$fullname = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname; 
	// 	$datereceived = Carbon\Carbon::parse($datereceived)->toDateString();
	// 	$details = "Equipment profiled on ".$datereceived. " by ". $fullname . ". ";
	// 	$title = 'Equipment Profiling';
	// 	$staff_id = Auth::user()->id;
	// 	$ticket_id = null;
	// 	$status = 'Closed';

	// 	/*
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	| 	Calls the function generate equipment ticket
	// 	|
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	*/
	
	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Receive'
	// 	]);

	// 	$ticket = new Ticket;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->parent_id = $ticket_id;
	// 	$ticket->status = $status;
	// 	$ticket->generate($item_id);
	// }

	/**
	*
	*	@param $item accepts item type name
	*	@return returns the list of propertynumber of type
	*
	*/
	// public static function getUnassignedPropertyNumber($item)
	// {
	// 	/*
	// 	*
	// 	*	Initialize item profile
	// 	*
	// 	*/
	// 	$item;

	// 	/**
	// 	*
	// 	*	queries all the itemtypes 
	// 	*	select only the top 
	// 	*	@return id
	// 	*
	// 	*/
	// 	$itemtype = ItemType::type($item)->select('id')->first();

	// 	/**
	// 	*
	// 	*	after selecting the itemtype where the item belongs
	// 	*	pluck all the id on the inventory
	// 	*	where the item type belongs
	// 	*
	// 	*/
	// 	$id = Inventory::where('itemtype_id','=',$itemtype->id)->select('id')->pluck('id');
	// 	//switch case items
	// 	switch( $item ){

	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		| System Unit
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	// 		case 'System Unit':
	// 		$item = Item::getListOfItems($id,'systemunit_id');
	// 		break;


	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		| Monitor
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	// 		case 'Display':
	// 		$item = Item::getListOfItems($id,'monitor_id');
	// 		break;

	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		| AVR
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	// 		case 'AVR':
	// 		$item = Item::getListOfItems($id,'avr_id');
	// 		break;

	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		| Keyboard
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	// 		case $item == 'Keyboard':
	// 		$item = Item::getListOfItems($id,'keyboard_id');
	// 		break;
	// 	}

	// 	/*
	// 	*
	// 	*	return collection of item profile
	// 	*
	// 	*/
	// 	return json_encode($item);
	// }

	/**
	*
	*	@param $id accepts item profile id
	*	@param $name filter the pluck returned
	*	@return collection of $name from $id found
	*
	*/
	// public static function getListOfItems($id,$name){
	// 	$item = Item::whereIn('inventory_id',$id)
	// 	              ->whereNotIn('id',Workstation::select($name)->pluck($name))
	// 	              ->select('property_number')
	// 	              ->get();
	// 	return $item;
	// }

	/**
	*
	*	@return query for unassembled item 	
	*
	*/
	// public function scopeUnassembled($query)
	// {
	// 	return $query->whereNotIn('id',Workstation::whereNotNull('systemunit_id')->pluck('systemunit_id'))
	// 				->whereNotIn('id',Workstation::whereNotNull('monitor_id')->pluck('monitor_id'))
	// 				->whereNotIn('id',Workstation::whereNotNull('keyboard_id')->pluck('keyboard_id'))
	// 				->whereNotIn('id',Workstation::whereNotNull('avr_id')->pluck('avr_id'));
	// }

	/**
	*
	*	@param item id
	*	@return item information
	*
	*/
	// public static function setItemStatus($id,$status)
	// {
	// 	$item = Item::find($id);
	// 	$item->status = $status;
	// 	$item->save();
	// 	return $item;
	// }

	/**
	*
	*	@param item id
	*	@param room id
	*
	*/
	// public static function setLocation($_item,$_room)
	// {
	// 	DB::beginTransaction();
	// 	/*
	// 	*	get the item profile
	// 	*	assign to $item variable
	// 	*/
	// 	$item = Item::find($_item);

	// 	/*
	// 	*	set item location
	// 	*	location is the room name
	// 	*/
	// 	$room = Room::location($_room)->first();
	// 	$item->location  = $room->id;
	// 	$item->save();

	// 	/*
	// 	*
	// 	*	create a transfer ticket
	// 	*
	// 	*/
	// 	$title = "Item Transfer";
	// 	$details = "Item's location has been set to $room->name";
	// 	$staff_id = Auth::user()->id;
	// 	$ticket_id = null;
	// 	$status = 'Closed';

	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Transfer'
	// 	]);

	// 	$ticket = new Ticket;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->parent_id = $ticket_id;
	// 	$ticket->status = $status;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->generate($_item);

	// 	DB::commit();

	// }

	// public function getIDFromPropertyNumber($propertynumber)
	// {
	// 	return Item::propertyNumber($propertynumber)->pluck('id');
	// }

}
