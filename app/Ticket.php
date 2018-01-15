<?php

namespace App;

use DB;
use Auth;
use App\Ticket;
use App\Pc;
use App\ItemProfile;
use App\PcTicket;
use App\RoomTicket;
use App\Room;
use Carbon\Carbon;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ticket extends \Eloquent{


	protected $table = 'tickets';
	public $timestamps = true;
	public $underrepair = "";
	public $undermaintenance = false;

	public $fillable = ['item_id','tickettype','ticketname','details','author','staffassigned','ticket_id','status'];
	protected $primaryKey = 'id';

	public static $rules = array(

		'Item Id' => 'required|exists:itemprofile,id',
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

	public function user()
	{
		return $this->hasOne('App\User','id','staffassigned');
	}

	public function itemprofile()
	{
		return $this->belongsToMany('App\ItemProfile','item_ticket','item_id','ticket_id');
	}

	public function room()
	{
		return $this->belongsToMany('App\Room','room_ticket','room_id','ticket_id');
	}

	public function pc()
	{
		return $this->belongsToMany('App\Pc','pc_ticket','pc_id','ticket_id');
	}

	public function scopeTickettype($query,$value)
	{
		return $query->where('tickettype','=',$value);
	}

	public function scopeStatus($query,$value)
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
		$this->attributes['tickettype'] = ucwords($value);
	}

	public function getPcTickets($id)
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
		if( ($pc = Pc::isPc($tag)) )
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
		else if(($itemprofile = ItemProfile::propertyNumber($tag))->count() > 0) 
		{
			return $itemprofile;
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

		$this->save();
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the equipment is connected to pc
		|
		|--------------------------------------------------------------------------
		|
		*/
		if( ($pc = Pc::isPc($tag)) )
		{
			if($this->undermaintenance) Pc::setItemStatus($pc->id,'undermaintenance');
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
		else if(($itemprofile = ItemProfile::propertyNumber($tag)->orWhere('id','=',$tag))->count() > 0) 
		{
			$itemprofile->first()->ticket()->attach($this->id);
			if($this->undermaintenance) ItemProfile::setItemStatus($itemprofile->id,'undermaintenance');
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
		$this->tickettype = $ticket->tickettype;
		$this->ticketname = $ticket->ticketname;
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
		$ticket->tickettype = 'action taken';
		$ticket->ticketname = 'Action Taken';
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
		$this->tickettype = 'condemn';
		$this->ticketname = 'Item Condemn';

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if the tag is equipment
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(($itemprofile = ItemProfile::propertyNumber($tag)->first())->count() > 0) $this->generate($itemprofile->id);
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
		if(($pc = PcTicket::ticket($tag)->first())->count() > 0)
		{
			Pc::setItemStatus($pc->pc_id,$status);
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
			ItemProfile::setItemStatus($itemticket->item_id,$status);
		}
	}

	/**
	*
	*	@param $tag accepts room name, property number of item
	*	@param $ticketname accepts ticket title
	*	@param $details accepts details
	*
	*/
	public function maintenance($tag,$ticketname,$details,$underrepair)
	{

		$this->staffassigned = Auth::user()->id;
		$this->status = 'Open';
		$this->ticket_id = null;
		$this->tickettype = 'maintenance';
		$this->generate();
	}

}
