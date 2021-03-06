<?php

namespace App\Models\Ticket;

// use DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Item\Item;
use App\Models\Ticket\Tag;
// use App\Models\Room\Room;
use App\Models\Ticket\Type;
// use App\Models\Workstation;
use Illuminate\Support\Facades\Auth;
use App\Http\Modules\Ticket\Mutable;
use App\Http\Modules\Ticket\Fetchable;
use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\Ticket\Filterable;
use App\Models\Workstation\Workstation;
use App\Http\Modules\Ticket\Questionable;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{

	use Filterable, Fetchable, Mutable, Questionable;

	const OPEN_STATUS = 'Open';
	const CLOSED_STATUS = 'Closed';
	const TRANSFERRED_STATUS = 'Transferred';
	const RESOLVED_STATUS = 'Resolved';

	protected $table = 'tickets';
	protected $primaryKey = 'id';
	public $timestamps = true;
	// public $underrepair = "";
	// public $undermaintenance = false;

	public $fillable = [
		'item_id', 'type_id', 'title', 'details', 'author', 'staff_id', 'ticket_id', 'status',
		'user_id', 'main_id', 'parent_id'
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
		'type_name', 'staff_name', 'human_readable_date'
	];

	/**
	 * Returns the type the ticket currently have
	 *
	 * @return string
	 */
	public function getTypeNameAttribute()
	{
		return $this->type->name ?? 'Not Set';
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
	 * Returns the parsed for human date format
	 *
	 * @return object
	 */
	public function getHumanReadableDateAttribute()
	{
		return Carbon::parse($this->date)->format('M d Y h:s a');
	}

	/**
	 * Generate a ticket code based on the current year and month
	 * The ticket code must only be generated after a ticket has been created
	 *
	 * @return void
	 */
	// public function getCodeAttribute()
	// {
	// 	return Carbon::now()->format('y') . '-' . Carbon::now()->format('m') . '-' . $this->id;
	// }

	// public function user()
	// {
	// 	return $this->belongsTo(User::class, 'user_id', 'id');
	// }

	/**
	 * Returns the user where the staff_id exists
	 *
	 * @return void
	 */
	public function staff()
	{
		return $this->belongsTo(User::class, 'staff_id', 'id');
	}

	/**
	 * Returns relationship with tags table
	 *
	 * @return void
	 */
	public function type()
	{
		return $this->belongsTo(Type::class, 'type_id', 'id');
	}

	/**
	 * Returns relationship with items table
	 *
	 * @return void
	 */
	public function item()
	{
		return $this->belongsToMany(Item::class, 'item_ticket', 'ticket_id', 'item_id');
	}

	/**
	 * Returns relationship with tags table
	 *
	 * @return void
	 */
	public function tags()
	{
		return $this->belongsToMany(Tag::class, 'tag_ticket',' ticket_id', 'tag_id');
	}

	// public function room()
	// {
	// 	return $this->belongsToMany(Room::class,'room_ticket','room_id','ticket_id');
	// }

	/**
	 * Returns relationship with parent ticket
	 *
	 * @return void
	 */
	public function parentTicket()
	{
		return $this->belongsTo(Ticket::class, 'parent_id', 'id');
	}

	/**
	 * Returns relationship with child ticket
	 *
	 * @return void
	 */
	public function childTickets()
	{
		return $this->hasMany(Ticket::class, 'parent_id', 'id');
	}

	/**
	 * Returns relationship with parent ticket
	 *
	 * @return void
	 */
	public function workstation()
	{
		return $this->belongsToMany(Workstation::class, 'workstation_ticket', 'ticket_id', 'workstation_id');
	}

	/**
	 * Fetch the current user as author
	 *
	 * @param Builder $query
	 * @return void
	 */
	public function scopeAuthorIsCurrentUser($query)
	{
		return $query->where('user_id', '=', Auth::user()->id);
	}

	/**
	 * Fetch where the parent id is null
	 *
	 * @param Builder $query
	 * @return void
	 */
	public function scopeRoot($query)
	{
		return $query->whereNull('parent_id');
	}

}
