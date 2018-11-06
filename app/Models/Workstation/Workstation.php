<?php

namespace App\Models\Workstation;

use App\Models\Room\Room;
use App\Models\Item\Item;
use App\Models\Ticket\Ticket;
use App\Models\Software\Software;
use Illuminate\Database\Eloquent\Model;

class Workstation extends Model
{

	protected $table = 'workstations';
	protected $primaryKey = 'id';
	public $timestamps = false;
	public $fillable = [
		'oskey', 'mouse', 'keyboard_id', 'systemunit_id', 'monitor_id', 'avr_id', 'name', 'room_id'
	];

	// public static $rules = array(
	// 	'License Key' => 'min:2|max:50',
	// 	'AVR' => 'exists:items,property_number',
	// 	'Monitor' => 'exists:items,property_number',
	// 	'System Unit' => 'required|exists:items,property_number',
	// 	'Keyboard' => 'exists:items,property_number',
	// 	'Mouse' => 'exists:items,local_id'
	// );

	protected $appends = [
		'system_unit_local', 'monitor_local', 'keyboard_local', 'avr_local', 'mouse_local', 'location'
	];

	/**
	 * Local id for system unit
	 *
	 * @return string
	 */
	public function getSystemUnitLocalAttribute()
	{
		return $this->systemunit->local_id ?: "None";
	}

	/**
	 * Local id for mouse
	 *
	 * @return string
	 */
	public function getMouseLocalAttribute()
	{
		return isset($this->mouse) ? $this->mouse->local_id : "None";
	}

	/**
	 * Local id for keyboard
	 *
	 * @return string
	 */
	public function getKeyboardLocalAttribute()
	{
		return isset($this->keyboard) ? $this->keyboard->local_id : "None";
	}

	/**
	 * Local id for avr
	 *
	 * @return string
	 */
	public function getAvrLocalAttribute()
	{
		return isset($this->avr) ? $this->avr->local_id : "None";
	}

	/**
	 * Local id for monitor
	 *
	 * @return string
	 */
	public function getMonitorLocalAttribute()
	{
		return isset($this->monitor) ? $this->monitor->local_id : "None";
	}

	/**
	 * Returns the location of workstation
	 *
	 * @return string
	 */
	public function getLocationAttribute()
	{
		return isset($this->room) ? $this->room->name : "None";
	}

	/**
	 * Links to the room tbale
	 *
	 * @return object
	 */
	public function room()
	{
		return $this->belongsTo(Room::class, 'room_id', 'id');
	}
	
	/**
	 * References system unit item table
	 *
	 * @return object
	 */
	public function systemunit()
	{
		return $this->belongsTo(Item::class, 'systemunit_id', 'id');
	}

	/**
	 * References monitor item table
	 *
	 * @return object
	 */
	public function monitor()
	{
		return $this->belongsTo(Item::class, 'monitor_id', 'id');
	}

	/**
	 * References keyboard item table
	 *
	 * @return object
	 */
	public function keyboard()
	{
		return $this->belongsTo(Item::class, 'keyboard_id', 'id');
	}

	/**
	 * References avr item table
	 *
	 * @return object
	 */
	public function avr()
	{
		return $this->belongsTo(Item::class, 'avr_id', 'id');
	}

	/**
	 * References mouse item table
	 *
	 * @return object
	 */
	public function mouse()
	{
		return $this->belongsTo(Item::class, 'mouse_id', 'id');
	}

	/**
	 * References software table
	 *
	 * @return object
	 */
	public function softwares()
	{
		return $this->belongsToMany(
			Software::class, 'workstation_software', 'workstation_id', 'software_id'
		)->withPivot('license_id', 'created_at', 'updated_at')->withTimestamps();
	}

	/**
	 * Filters the query by property number
	 *
	 * @param Builder $query
	 * @param string $propertyNumber
	 * @return object
	 */
	public function scopePropertyNumber($query, $propertyNumber)
	{
		return $query->where('property_number', $propertyNumber);
	}

	/**
	 * Filters the query by property number list
	 *
	 * @param Builder $query
	 * @param string $listOfPropertyNumbers
	 * @return object
	 */
	public function scopeinPropertyNumber($query, $listOfPropertyNumbers)
	{
		return $query->whereIn('property_number', $listOfPropertyNumbers);
	}

	/**
	 * Links to ticket table
	 *
	 * @return object
	 */
	public function tickets()
	{
		return $this->belongsToMany(Ticket::class, 'workstation_ticket', 'workstation_id', 'ticket_id');
	}

	// public function scopeName($query, $value)
	// {
	// 	return $query->where('name', '=', $value);
	// }

    // public function assemble()
    // {
    // 	$this->name = $this->generateWorkstationName();
	// 	$this->save();

	// 	$details = "";

	// 	/*
	// 	*
	// 	*	Create a workstation ticket
	// 	*	The current person who assembles the workstation will receive the ticket
	// 	*	Details are autogenerated by the system
	// 	*
	// 	*/
	// 	if(isset($this->name))
	// 	{
	// 		$details = 'Workstation ' . $this->name . ' assembled with the following property number:';			
	// 	}
	// 	else
	// 	{
	// 		$details = 'Workstation assembled with the following property number:';
	// 	}

	// 	if(isset($this->systemunit_id))
	// 	{

	// 		$details = $details . $this->systemunit_id . ' for System Unit. ' ;
	// 	}

	// 	if(isset($this->monitor_id))
	// 	{
	// 		$details = $details . $this->monitor_id . ' for Monitor. ';
	// 	}

	// 	if(isset($this->keyboard_id))
	// 	{
	// 		$details = $details . $this->keyboard_id . ' for Keyboard. ';
	// 	}

	// 	if(isset($this->avr_id))
	// 	{
	// 		$details = $details . $this->avr_id . ' for AVR. ';
	// 	}

	// 	if(isset($this->mouse_id))
	// 	{
	// 		$details = $details . $this->mouse_id . ' for mouse. ';
	// 	}

	// 	$title = 'Workstation Assembly';
	// 	$staff_id = Auth::user()->id;

	// 	$type = TicketType::firstOrCreate([
	// 		'name' => 'Receive'
	// 	]);

	// 	$ticket = new Ticket;
	// 	$ticket->type_id = $type->id;
	// 	$ticket->title = $title;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->status = 'Closed';
	// 	$ticket->generate($this->systemunit->id);
    // }

    // public function updateParts()
    // {

	// 	$details = "";

	// 	/*
	// 	*
	// 	*	Create a workstation ticket
	// 	*	The current person who assembles the workstation will receive the ticket
	// 	*	Details are autogenerated by the system
	// 	*
	// 	*/
	// 	if(isset($this->name))
	// 	{
	// 		$details = 'Workstation ' . $this->name . ' update with the following property number:';			
	// 	}
	// 	else
	// 	{
	// 		$details = 'Workstation assembled with the following property number:';
	// 	}

	// 	if(isset($this->systemunit_id))
	// 	{

	// 		$details = $details . $this->systemunit_id . ' for System Unit. ' ;
	// 	}

	// 	if(isset($this->monitor_id))
	// 	{
	// 		$details = $details . $this->monitor_id . ' for Monitor. ';
	// 	}

	// 	if(isset($this->keyboard_id))
	// 	{
	// 		$details = $details . $this->keyboard_id . ' for Keyboard. ';
	// 	}

	// 	if(isset($this->avr_id))
	// 	{
	// 		$details = $details . $this->avr_id . ' for AVR. ';
	// 	}

	// 	if(isset($this->mouse_id))
	// 	{
	// 		$details = $details . $this->mouse_id . ' for mouse. ';
	// 	}
		
    // 	$this->name = $this->generateWorkstationName();
	// 	$this->save();

	// 	$name = 'Workstation Update';
	// 	$staffassigned = Auth::user()->id;

	// 	$ticket = new Ticket;
	// 	$ticket->type = 'Maintenance';
	// 	$ticket->name = $name;
	// 	$ticket->details = $details;
	// 	$ticket->staffassigned = $staffassigned;
	// 	$ticket->status = 'Closed';
	// 	$ticket->generate($this->systemunit->id);
    // }

    // public static function condemn($id,$systemunit,$monitor,$keyboard,$avr)
    // {

    // 	$pc = Workstation::find($id);

    // 	if($systemunit)
    // 	{
    // 		if(isset($pc->systemunit_id))
    // 		{	
    // 			Inventory::condemn($pc->systemunit_id);
    // 		}	
    // 	}

    // 	if($monitor)
    // 	{
    // 		if(isset($pc->monitor_id))
    // 		{
    // 			Inventory::condemn($pc->monitor_id);
    // 		}
    // 	}

    // 	if($keyboard)
    // 	{
    // 		if(isset($pc->keyboard_id))
    // 		{
    // 			Inventory::condemn($pc->keyboard_id);
    // 		}
    // 	}

    // 	if($avr)
    // 	{
    // 		if(isset($pc->avr_id))
    // 		{
    // 			Inventory::condemn($pc->avr_id);
    // 		}
    // 	}

	// 	$name = 'Workstation Condemn';
	// 	$staffassigned = Auth::user()->id;
	// 	$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " . Auth::user()->lastname;
    // 	$details = `Workstation condemned on` . Carbon::now()->toDayDateTimeString() . 'by ' . $author;

	// 	$ticket = new Ticket;
	// 	$ticket->type = 'Maintenance';
	// 	$ticket->name = $name;
	// 	$ticket->details = $details;
	// 	$ticket->staff_id = $staff_id;
	// 	$ticket->status = 'Closed';
	// 	$ticket->author = $author;
	// 	$ticket->generate($this->systemunit->id);

    // 	$pc->delete();
    // }

    /**
    *
    *	@param $object accepts object collection
    *	get the id from object
    *	returns null if no id
    *
    */
    // public static function getID($object)
    // {
    // 	if(isset($object->id))
    // 	{
    // 		$object = $object->id;
    // 		return $object;
    // 	}

	// 	return null;
    // }

    /**
    *
    *	@param $property number of item
    *	@return null or pc details
    *
    */
    // public static function isWorkstation($tag)
    // {
		    
	// 	/*
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	| 	Check if property number exists
	// 	|
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	*/
	// 	$item = Item::propertyNumber($tag)->first();
    // 	if( $item && $item->count() > 0) 
    // 	{
		    
		    
	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	query if id is in pc
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	//     	$pc = Workstation::where('systemunit_id', '=', $item->id)
	//     		->orWhere('monitor_id','=',$item->id)
	//     		->orWhere('avr_id','=',$item->id)
	//     		->orWhere('keyboard_id','=',$item->id)
	//     		->orWhere('mouse_id','=',$item->id)
	//     		->first();
		    
	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	Check if pc exists 
	// 		|	If existing return id
	// 		|	return null if not
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	//     	if( $pc && $pc->count() > 0 )
	//     	{
	//     		return $pc;
	//     	}
	//     	else
	//     	{

	// 			/*
	// 			|--------------------------------------------------------------------------
	// 			|
	// 			| 	If it doesnt exists
	// 			|	check if the tag is pc name
	// 			|	return null if not
	// 			|
	// 			|--------------------------------------------------------------------------
	// 			|
	// 			*/
	// 			$pc = Workstation::name($tag)->first();
	// 			if( $pc && $pc->count() > 0)
	// 			{
	// 				return $pc;
	// 			}

	//     		return null;
	//     	}
    // 	} 
    // 	else 
    // 	{
		    
	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	If it doesnt exists
	// 		|	check if the tag is pc name
	// 		|	return null if not
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	// 		$pc = Workstation::name($tag)->first();
	// 		if( $pc && $pc->count() > 0)
	// 		{
	// 			return $pc;
	// 		}
	// 		else
	// 		{

	// 	    	$pc = Workstation::where('systemunit_id', '=', $tag)
	// 	    		->orWhere('monitor_id','=',$tag)
	// 	    		->orWhere('avr_id','=',$tag)
	// 	    		->orWhere('keyboard_id','=',$tag)
	// 	    		->orWhere('mouse_id','=',$tag);

	// 	    	if($pc->count() > 0) return $pc->first();
	// 		}
			
	// 		return null;
    // 	}
    // }

    /**
    *
    *	@param $id accepts pc id
    *	@param $status accepts status to set 'for repair' 'working' 'condemned'
    *	@param $monitor accepts monitor
    *	@param $keyboard accepts keyboard
    *	@param $avr accepts avr
    *	@param $system unit accepts system unit
    *	@return pc information
    *
    */
    // public static function setItemStatus($id,$status,$monitor = true, $keyboard = true, $avr = true, $systemunit = true)
    // {
    // 	$pc = Workstation::find($id);
 	// 	DB::transaction(function() use ($pc,$status,$avr,$systemunit,$keyboard,$monitor){
	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	System Unit
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	//     	if($systemunit)
	//     	{
	//     		if( isset($pc->systemunit_id) )
	//     		{
	//     			Item::setItemStatus($pc->systemunit_id,$status);
	//     		}
	//     	}

	//  		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	Monitor
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	//     	if($monitor)
	//     	{
	//     		if( isset($pc->monitor_id) )
	//     		{
	//     			Item::setItemStatus($pc->monitor_id,$status);
	//     		}
	//     	}

	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	Keyboard
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	//     	if($keyboard)
	//     	{
	//     		if( isset($pc->keyboard_id) )
	//     		{
	//     			Item::setItemStatus($pc->keyboard_id,$status);
	//     		}
	//     	}

	// 		/*
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		| 	AVR
	// 		|
	// 		|--------------------------------------------------------------------------
	// 		|
	// 		*/
	//     	if($avr)
	//     	{
	//     		if( isset($pc->avr_id) )
	//     		{
	//     			Item::setItemStatus($pc->avr_id,$status);
	//     		}
	//     	}
	//     });

	// 	/*
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	| 	PC Information
	// 	|
	// 	|--------------------------------------------------------------------------
	// 	|
	// 	*/
    // 	return $pc;
    // }

    // /**
    // *
    // *	@param $pc is a comma separated id of each pc
    // *	@param room accepts room name
    // *
    // */
    // public static function setWorkstationLocation($pc,$room)
    // {

    // 	$room = Room::location($room)->first();
	// 	$pc = Workstation::find($pc);
	// 	$pc->room_id = $room->id;

	// 	if(isset($pc->systemunit_id))
	// 	{
	// 		$pc->systemunit()->update([
	// 			'location' => $room->id
	// 		]);
	// 	}

	// 	if(isset($pc->avr_id))
	// 	{
	// 		$pc->avr()->update([
	// 			'location' => $room->id
	// 		]);
	// 	}

	// 	if(isset($pc->keyboard_id))
	// 	{
	// 		$pc->keyboard()->update([
	// 			'location' => $room->id
	// 		]);
	// 	}

	// 	if(isset($pc->monitor_id))
	// 	{
	// 		$pc->monitor()->update([
	// 			'location' => $room->id
	// 		]);
	// 	}

	// 	$pc->save();

	// 	$title = "Item Transfer";
	// 	$details = "Workstation location has been set to $room->name";
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
	// 	$ticket->generate($pc->systemunit->id);
    // }
}
