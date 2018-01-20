<?php

namespace App;

use DB;
use Auth;
use Carbon\Carbon;
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
		'Ticket Id' => 'exists:ticket,id',
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
		'Ticket ID' => 'required|exists:ticket,id',
		'Staff Assigned' => 'required|exists:user,id',
	);

	protected $appends = [
		'ticket_type_name'
	];

	public function getTicketTypeNameAttribute()
	{
		return $this->type->name;
	}

	public function user()
	{
		return $this->hasOne('App\User','id','staffassigned');
	}

	public function type()
	{
		return $this->belongsTo('App\TicketType', 'type_id', 'id');
	}

	public function item()
	{
		return $this->belongsToMany('App\Item','item_ticket','item_id','ticket_id');
	}

	public function room()
	{
		return $this->belongsToMany('App\Room','room_ticket','room_id','ticket_id');
	}

	public function pc()
	{
		return $this->belongsToMany('App\Workstation','pc_ticket','pc_id','ticket_id');
	}

	public function scopeFindByType($query,$value)
	{
		return $query->where('type','=',$value);
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

	public function getWorkstationTickets($id)
	{
		return Ticket::whereIn('id',function($query) use ($id)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	checks if pc is in ticket
			|
			|--------------------------------------------------------------------------
			|
			*/
			$query->where('pc_id','=',$id)
				->from('pc_ticket')
				->select('ticket_id')
				->pluck('ticket_id');
		})->get();
	}

	public function getRoomTickets($id)
	{
		return Ticket::whereIn('id',function($query) use ($id)
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	checks if pc is in ticket
			|
			|--------------------------------------------------------------------------
			|
			*/
			$query->where('room_id','=',$id)
				->from('room_ticket')
				->select('ticket_id')
				->pluck('ticket_id');
		})->get();
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
			return $item;
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
			return $room;
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
		
		if( $this->author == null || !isset($this->author))
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
			$pc->ticket()->attach($this->id);
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
			$item->first()->ticket()->attach($this->id);
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
			$room->ticket()->attach($this->id);
		}
		
	}

	public function copyRecordFromExisting($ticket)
	{
		$this->type = $ticket->type;
		$this->title = $ticket->title;
		$this->details = $ticket->details;
		$this->author = $ticket->author;
		$this->staffassigned = $ticket->staffassigned;
		$this->ticket_id = $ticket->id;
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
		$ticket->type = 'action taken';
		$ticket->title = 'Action Taken';
		$ticket->ticket_id = $this->id;
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
		$date = Carbon::now()->toFormattedDateTimeString();
		$this->details = 'Item Condemned on ' . $date . 'by ' . $this->author;
		$this->staffassigned = Auth::user()->id;
		$this->ticket_id = null;
		$this->status = 'Closed';
		$this->type = 'condemn';
		$this->title = 'Item Condemn';

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(($item = Item::propertyNumber($tag)->first())->count() > 0) $this->generate($item->id);
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

		$this->staffassigned = Auth::user()->id;
		$this->status = 'Open';
		$this->ticket_id = null;
		$this->type = 'maintenance';
		$this->generate();
	}

}
