<?php

namespace App;

use DB;
use Auth;
use Carbon;
use App\Models\Tag;
use App\Models\User;
use App\Models\Room\Room;
use App\Models\Item\Item;
use App\Models\Ticket\Type;
use App\Models\Workstation;
use App\Http\Modules\Ticket\Mutable;
use App\Http\Modules\Ticket\Fetchable;
use App\Http\Modules\Ticket\Filterable;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

	use Filterable, Fetchable, Mutable;

	protected $table = 'tickets';
	protected $primaryKey = 'id';
	public $timestamps = true;
	const OPEN_STATUS = 'Open';
	const CLOSED_STATUS = 'Closed';
	const TRANSFERRED_STATUS = 'Transferred';
	// public $underrepair = "";
	// public $undermaintenance = false;

	public $fillable = [
		'item_id', 'type_id', 'title', 'details', 'author', 'staff_id', 'ticket_id', 'status'
	];

	// public static $rules = array(
	// 	'Subject' => 'required|min:2|max:100',
	// 	'Details' => 'required|min:2|max:500',
	// 	'Staff' => 'nullable|exists:users,id',
	// 	'Type' => 'required|exists:ticket_types,id',
	// );

	// public static $complaintRules = array(
	// 	'Ticket Subject' => 'required|min:2|max:100',
	// 	'Details' => 'required|min:2|max:500',
	// 	'Staff' => 'nullable|exists:users,id'
	// );

	// public static $maintenanceRules = array(
	// 	'Details' => 'required|min:2|max:500',
	// );

	// public static $resolveRules = array(
	// 	'Details' => 'required|min:2|max:500',
	// );

	// public static $transferRules = array(
	// 	'Ticket ID' => 'required|exists:tickets,id',
	// 	'Staff Assigned' => 'required|exists:users,id',
	// );

	protected $appends = [
		'ticket_type_name', 'ticket_code', 'tag_list', 'staff_name', 'parsed_date'
	];

	/**
	 * Returns the parsed for human date format
	 *
	 * @return object
	 */
	public function getParsedDateAttribute()
	{
		return Carbon\Carbon::parse($this->date)->diffForHumans();
	}

	/**
	 * Get the fullname of staff who is currently assigned to the ticket
	 * Returns none if there are no staff currently assigned
	 *
	 * @return string
	 */
	public function getStaffNameAttribute()
	{
		if( isset($this->staff) && $this->staff->count() > 0) {
			return $this->staff->firstname . " " . $this->staff->middlename . " " . $this->staff->lastname;
		}

		return 'None';
	}

	/**
	 * List all the tags the ticket currently have
	 *
	 * @return string
	 */
	public function getTagListAttribute()
	{
		return implode($this->tags->pluck('name')->toArray(), ',');
	}

	/**
	 * Returns the type the ticket currently have
	 *
	 * @return stroing
	 */
	public function getTicketTypeNameAttribute()
	{
		return $this->type->name ?? 'Not Set';
	}

	/**
	 * Generate a ticket code based on the current year and month
	 * The ticket code must only be generated after a ticket has been created
	 *
	 * @return void
	 */
	public function generateTicketCode()
	{
		$date = Carbon::now();

		$this->code = $date->format('y') . '-' . $date->format('m') . '-' . $this->id;
		$this->save();
	}

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function staff()
	{
		return $this->belongsTo(User::class, 'staff_id', 'id');
	}

	public function type()
	{
		return $this->belongsTo(Type::class, 'type_id', 'id');
	}

	public function item()
	{
		return $this->belongsToMany(Item::class, 'item_ticket', 'item_id', 'ticket_id');
	}

	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'tag_ticket', 'tag_id',' ticket_id');
	}

	public function room()
	{
		return $this->belongsToMany(Room::class,'room_ticket','room_id','ticket_id');
	}

	public function parentTicket()
	{
		return $this->belongsTo(Ticket::class, 'parent_id', 'id');
	}

	public function childTickets()
	{
		return $this->hasMany(Ticket::class, 'parent_id', 'id');
	}

	public function pc()
	{
		return $this->belongsToMany(Workstation::class, 'workstation_ticket', 'workstation_id', 'ticket_id');
	}

	public static function setStatusOfLinkedObjects($tag, $status)
	{

		// if the ticket exists in the workstation, set also the status of the
		// workstation
		if(($workstationTicket = WorkstationTicket::ticket($tag)->first())->count() > 0)
		{
			Workstation::setItemStatus($workstationTicket->workstation_id, $status);
		}

		// if the ticket exists in the item, set also the status of the
		// item
		else if( ($item = ItemTicket::ticketID($tag)->first())->count() > 0)
		{
			Item::setItemStatus($item->item_id, $status);
		}

		return $this;
	}

	public function generate($tag = null)
	{
		
		if(!isset($this->author) || $this->author == null)
		{
			$user = Auth::user();
			$author = $user->firstname . " " . $user->middlename . " " . $user->lastname;
			$this->attributes['author'] = $author;
		}

		$this->main_id = isset($this->main_id) ? $this->main_id : null;
		$this->user_id = Auth::user()->id;

		$this->save();
		
		
		// check if the tag is connected to a workstation
		if( $pc = Workstation::isWorkstation($tag) )
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

}