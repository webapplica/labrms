<?php

namespace App;

use DB;
use Auth;
use Carbon;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ticket extends \Eloquent{

	protected $table = 'tickets';
	public $timestamps = true;
	public $underrepair = "";
	public $undermaintenance = false;

	public $fillable = ['item_id','type','title','details','author','staff_id','ticket_id','status'];
	protected $primaryKey = 'id';

	public static $rules = array(

		'Item Id' => 'required|exists:items,id',
		'Ticket Type' => 'required|min:2|max:100',
		'Ticket Name' => 'required|min:2|max:100',
		'Details' => 'required|min:2|max:500',
		'Ticket Id' => 'exists:tickets, id',
		'Status' => 'boolean'
	);

	public static $complaintRules = array(
		'Ticket Subject' => 'required|min:2|max:100',
		'Details' => 'required|min:2|max:500',
	);

	public static $maintenanceRules = array(
		'Details' => 'required|min:2|max:500',
	);

	public static $resolveRules = array(
		'Details' => 'required|min:2|max:500',
	);

	public static $transferRules = array(
		'Ticket ID' => 'required|exists:tickets, id',
		'Staff Assigned' => 'required|exists:users, id',
	);

	protected $appends = [
		'ticket_type_name', 'ticket_code', 'tag_list', 'staff_name', 'parsed_date'
	];

	public function getParsedDateAttribute()
	{
		return Carbon\Carbon::parse($this->date)->diffForHumans();
	}

	public function getStaffNameAttribute()
	{
		if(isset($this->staff) && count($this->staff) > 0)
			return $this->staff->firstname . " " . $this->staff->middlename . " " . $this->staff->lastname;
		return 'None';
	}

	public function getTagListAttribute()
	{
		return implode( $this->tags->pluck('name')->toArray(), ',');
	}

	public function getTicketTypeNameAttribute()
	{
		return (isset($this->type) && count($this->type) > 0) ? $this->type->name : 'Not Set';
	}

	public function getTicketCodeAttribute()
	{
		$date = Carbon\Carbon::now();
		return $date->format('y') . '-' . $date->format('m') . '-' . $this->id;
	}

	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

	public function staff()
	{
		return $this->belongsTo('App\User', 'staff_id', 'id');
	}

	public function type()
	{
		return $this->belongsTo('App\TicketType', 'type_id', 'id');
	}

	public function item()
	{
		return $this->belongsToMany('App\Item','item_ticket','item_id','ticket_id');
	}

	public function tags()
	{
		return $this->belongsToMany('App\Tag','tag_ticket','tag_id','ticket_id');
	}

	public function room()
	{
		return $this->belongsToMany('App\Room','room_ticket','room_id','ticket_id');
	}

	public function pc()
	{
		return $this->belongsToMany('App\Workstation','workstation_ticket','workstation_id','ticket_id');
	}

	public function scopeFindByType($query,$value)
	{
		return $query->whereHas('type', function($query) use ($value){
			$query->where('name', '=', $value);
		});
	}

	public function scopeFindByStatus($query,$value)
	{
		return $query->where('status','=',$value);
	}

	public function scopeStaffassigned($query,$value)
	{
		return $query->where('staffassigned','=',$value);
	}

	public function scopeOpen($query)
	{
		return $query->where('status','=','Open');
	}

	public function scopeSelfAuthored($query)
	{
		$user = Auth::user();
		return $query->where('user_id', '=', $user->id);
	}

	public function scopeSelfAssigned($query)
	{
		$user = Auth::user();
		return $query->where('staff_id', '=', $user->id);
	}

	public function scopeClosed($query)
	{
		return $query->where('status','=','Closed');
	}

	public function getTickettypeAttribute($value)
	{
		return ucwords($value);
	}

	public function getDetailsAttribute($value)
	{
		return ucwords($value);
	}

	public function getTicketnameAttribute($value)
	{
		return ucwords($value);
	}

	public function setTickettypeAttribute($value)
	{
		$this->attributes['type'] = ucwords($value);
	}

	public function scopeFindAllWorkstationTickets($id)
	{
		return Ticket::whereHas('workstation',function($query) use ($id)
		{
			$query->where('id','=',$id);
		});
	}

	public function scopeFindAllRoomTickets($id)
	{
		return Ticket::whereHas('room',function($query) use ($id)
		{
			$query->where('id','=',$id);
		});
	}

	public function getTagDetails($tag)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the equipment is connected to pc
		|
		|--------------------------------------------------------------------------
		|
		*/
		if( ($pc = Workstation::isWorkstation($tag)) )
		{
			return $pc;
		} 

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		else if(($item = Item::propertyNumber($tag))->count() > 0)  
		{
			return $item->first();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is room
		|
		|--------------------------------------------------------------------------
		|
		*/
		else if(($room = Room::location($tag))->count() > 0) 
		{
			return $room->first();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	return false if no item found
		|
		|--------------------------------------------------------------------------
		|
		*/
		return null;
	}

	public function generate($tag = null)
	{
		
		if(!isset($this->author) || $this->author == null)
		{
			$user = Auth::user();
			$author = $user->firstname . " " . $user->middlename . " " . $user->lastname;
			$this->attributes['author'] = $author;
		}

		$this->user_id = Auth::user()->id;

		$this->save();
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the equipment is connected to pc
		|
		|--------------------------------------------------------------------------
		|
		*/
		if( ($pc = Workstation::isWorkstation($tag)) )
		{
			if($this->undermaintenance) Workstation::setItemStatus($pc->id,'undermaintenance');
			$pc->tickets()->attach($this->id);
		} 

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		else if(($item = Item::propertyNumber($tag)->orWhere('id','=',$tag))->count() > 0) 
		{
			$item->first()->tickets()->attach($this->id);
			if($this->undermaintenance) Item::setItemStatus($item->id,'undermaintenance');
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is room
		|
		|--------------------------------------------------------------------------
		|
		*/
		else if(($room = Room::location($tag))->count() > 0) 
		{
			$room->first()->tickets()->attach($this->id);
		}
		
	}

	public function copyRecordFromExisting($ticket)
	{
		$this->type = $ticket->type;
		$this->title = $ticket->title;
		$this->details = $ticket->details;
		$this->author = $ticket->author;
		$this->staff_id = $ticket->staff_id;
		$this->parent_id = $ticket->id;
		$this->status = $ticket->status;
		$this->comments = $ticket->comments;
		$this->closed_by = $ticket->closed_by;
		$this->validated_by = $ticket->validated_by;
		$this->deadline = $ticket->deadline;
		$this->trashable = $ticket->trashable;
		$this->severity = $ticket->severity;
		$this->nature = $ticket->nature;
		// $this-> = $ticket->;
	}

	/**
	*
	*	@return set the current ticket as transferred
	*
	*/
	public function transfer()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function close ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		$ticket = Ticket::find($this->id);
		$ticket->status = 'Transferred';
		$ticket->save();

		$_ticket = new Ticket;
		$_ticket->copyRecordFromExisting($this);
		$_ticket->generate();
	}

	/**
	*
	*	@param $id accepts id
	*	@return $ticket object generated
	*
	*/
	public function close()
	{
		$author = Auth::user()->firstname . " " . Auth::user()->middlename . " " .Auth::user()->lastname;
		$this->closed_by = $author;
		$this->status = 'Closed';
		$this->save();
	}

	/**
	*
	*	@param $id accepts ticket id
	*	@param $details accepts details
	*	@param $status receives either 'Closed' or 'Open'
	*	@param $underrepair receives boolean value
	*
	*/
	public function resolve()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function close ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($this->status == 'Closed') $this->close();

		/*
		|--------------------------------------------------------------------------
		|
		| 	set the item status to underrepair
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($this->underrepair == 'undermaintenance' || $this->underrepair == 'working')
		{
			$this->setTaggedStatus($this->id,$underrepair);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	call function generate ticket
		|
		|--------------------------------------------------------------------------
		|
		*/
		$ticket = new Ticket;
		$ticket->copyRecordFromExisting($this);
		$ticket->comments = "";
	
		$type = TicketType::firstOrCreate([
			'name' => 'Action'
		]);

		$this->type_id = $type->id;
		$ticket->parent_id = $this->id;
		$ticket->status = 'Closed';
		$ticket->generate();
	}

	public function reopen()
	{
		$this->status = "Closed";
		$this->save();

		$ticket = new Ticket;
		$ticket->copyRecordFromExisting($this);
		$ticket->status = "Open";
		$ticket->author = null;
		$ticket->generate();
	}

	public function condemn($tag)
	{
		$date = Carbon\Carbon::now()->toDayDateTimeString();
		$this->details = 'Item Condemned on ' . $date . 'by ' . $this->author;
		$this->staff_id = Auth::user()->id;
		$this->parent_id = null;
		$this->status = 'Closed';
	
		$type = TicketType::firstOrCreate([
			'name' => 'Condemn'
		]);

		$this->type_id = $type->id;
		$this->title = 'Item Condemn';

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		$this->generate($tag);
	}

	public static function setTaggedStatus($tag,$status)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the equipment is connected to pc
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(($pc = WorkstationTicket::ticket($tag)->first())->count() > 0)
		{
			Workstation::setItemStatus($pc->pc_id,$status);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		else if( ($itemticket = ItemTicket::ticketID($tag)->first())->count() > 0)
		{
			Item::setItemStatus($itemticket->item_id,$status);
		}
	}

	/**
	*
	*	@param $tag accepts room name, property number of item
	*	@param $title accepts ticket title
	*	@param $details accepts details
	*
	*/
	public function maintenance($tag,$title,$details,$underrepair)
	{

		$this->staff_id = Auth::user()->id;
		$this->status = 'Open';
		$this->parent_id = null;
	
		$type = TicketType::firstOrCreate([
			'name' => 'Maintenance'
		]);

		$this->type_id = $type->id;

		$this->generate();
	}

}
