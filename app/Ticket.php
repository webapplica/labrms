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

	protected $table = 'ticket';
	public $timestamps = true;

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

	public function setTickettypeAttribute($value)
	{
		$this->attributes['tickettype'] = ucwords($value);
	}

	public function getTickettypeAttribute($value)
	{
		return ucwords($value);
	}

	public function scopeOpen($query)
	{
		return $query->where('status','=','Open');
	}

	public function scopeClosed($query)
	{
		return $query->where('status','=','Closed');
	}

	public function getDetailsAttribute($value)
	{
		return ucwords($value);
	}

	public function getTicketnameAttribute($value)
	{
		return ucwords($value);
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
		$pc = Pc::isPc($tag);
		if(count($pc) > 0)
		{
			$pc = Pc::with('systemunit')->with('monitor')->with('keyboard')->with('avr')->find($pc->id);
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
		$itemprofile = ItemProfile::propertyNumber($tag)->first();
		if( count($itemprofile) > 0)
		{
			/*
			|--------------------------------------------------------------------------
			|
			| 	Create equipment ticket
			|
			|--------------------------------------------------------------------------
			|
			*/
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
		$room = Room::location($tag)->first();
		if( count($room) > 0 )
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

}
