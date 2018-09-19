<?php

namespace App\Models\Reservation;

use Auth;
use Carbon\Carbon;
use App\Models\Events\Special;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
	protected $table = 'reservations';
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $dates = [ 'timein', 'timeout' ];
	public $fillable = [
		'purpose',
		'user_id',
		'faculty_id',
		'location',
		'start',
		'end',
		'is_approved',
		'is_claimed',
		'is_cancelled',
	];

	public static $rules = [
		'Items' => 'required',
		'Location' => 'required|exists:rooms,id',
		'Time started' => 'required|date',
		'Time end' => 'required|date',
		'Purpose' => 'required',
		'Faculty-in-charge' => 'nullable|exists:faculties,id'
	];

	public static $roomReservationRules = [
		'Room' => 'required|exists:room,id',
		'Room Name' => 'required|between:4,100',
		'Time started' => 'required|date',
		'Time end' => 'required|date',
		'Purpose' => 'required',
		'Faculty-in-charge' => 'required|between:5,50'
	];

	public static $updateRules = [
		'Location' => 'required|between:4,100',
		'Time started' => 'required|date',
		'Time end' => 'required|date',
		'Purpose' => 'required',
		'Faculty-in-charge' => 'required|between:5,50'
	];

	public function user()
	{
		return $this->belongsTo('App\Models\User','user_id','id');
	}

	public function item()
	{
		return $this->belongsToMany('App\Item','item_reservation','reservation_id','item_id'); 
	}

	public function room()
	{
		return $this->belongsToMany('App\Room','room_reservation','reservation_id','room_id');
	}

	public function room_location()
	{
		return $this->belongsTo('App\Room', 'location', 'id');
	}

	protected $appends = [
		'reservee_name', 'parsed_date_and_time', 'status_name', 'room_name'
	];

	public function getRoomNameAttribute()
	{
		$room_name = $this->room_location->name;
		return $room_name;	
	}

	public function getStatusNameAttribute()
	{
		if( $this->is_disapproved ) {
			return 'disapproved';
		} else if( $this->is_cancelled ) {
			return 'cancelled';
		} else if( $this->is_claimed ) {
			return 'claimed';
		} else if ( $this->is_approved ) { 
			return 'approved';
		} else {
			return 'pending';
		}
	}

	public function getReserveeNameAttribute()
	{
		return  trim("{$this->user->lastname},{$this->user->firstname} {$this->user->middlename}");
	}

	public function getParsedDateAndTimeAttribute()
	{
		return Carbon::parse($this->end)->toDayDateTimeString();
	}

	public function scopeUnclaimed($query)
	{
		return $query->where(function($query){
			$query->whereNull('is_claimed');
		});
	}

	public function scopeApproved($query)
	{
		return $query->where('is_approved','=',1);
	}

	public function scopeDisapproved($query)
	{
		return $query->where('is_approved','=',2);
	}

	public function scopeUndecided($query)
	{
		return $query->where('is_approved','=',0);
	}

	public function scopeWithInfo($query)
	{
		$query = $query->with('itemprofile.inventory.itemtype')
					->with('user');
		if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4 )
		{
			$query = $query->where('user_id','=',Auth::user()->id);
		}

		return $query;
					
	}

	public function scopeUser($query,$id)
	{
		return $query->where('user_id','=',$id);
	}

	/**
	*	change reservation status to approved
	*	@param reservation id
	*	@return reservation information
	*/
	public static function approve($id)
	{
		$reservation = Reservation::find($id);
		$reservation->is_approved = Carbon::now();
		$reservation->save();

		return $reservation;
	}

	/**
	*	change reservation status to disapproved
	*	@param reservation id
	*	@param reservation reason
	*	@return reservation information
	*/
	public static function disapprove($id,$reason)
	{
		$reservation = Reservation::find($id);
		$reservation->is_disapproved = Carbon::now();
		$reservation->remarks = $reason;
		$reservation->save();
		
		return $reservation;
	}

	/**
	*
	*	check if there is existing reservation
	*	@param start time of reservation
	*	@param end time of reservation
	*	@return false if not
	*	@return reservation info
	*
	*/
	public static function hasReserved($start,$end)
	{
		$reservations = Reservation::whereBetween('timein',[$start->startOfDay(),$start->endOfDay()])
							->approved()
							->get();
		foreach($reservations as $reservation)
		{
			$dateofuse = Carbon::parse($reservation->time_start);
			if(Carbon::parse($start)->isSameDay($dateofuse))
			{
				$timein = Carbon::parse($reservation->timein);
				$timeout = Carbon::parse($reservation->timeout);

				/*
				|--------------------------------------------------------------------------
				|
				| 	current starting time of reservation is between existing reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($start)->between( $timein , $timeout ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	current ending time of reservation is between existing reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($end)->between( $timein , $timeout ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	existing reservation start is between current start and end of current reservation
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($timein)->between( $start , $end ) )
				{
					return $reservation;
				}

				/*
				|--------------------------------------------------------------------------
				|
				| 	existing reservation end is between current start and end of current reservation 
				|
				|--------------------------------------------------------------------------
				|
				*/
				if( Carbon::parse($timeout)->between( $start , $end ) )
				{
					return $reservation;
				}


			}
		}

		return false;
	}

	/**
	*
	*	set reservation status to 'claimed'
	*	@param id
	*	@return reservation
	*
	*/
	public static function setStatusAsClaimed($id)
	{
		$reservation = "";

		/*
		|--------------------------------------------------------------------------
		|
		| 	find the reservation information
		|
		|--------------------------------------------------------------------------
		|
		*/
		$reservation = Reservation::find($id);

		/*
		|--------------------------------------------------------------------------
		|
		|	if reservation is found
		| 	set status to claimed 
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(count($reservation) > 0)
		{
			$reservation->remarks = 'Claimed';
			$reservation->save();
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	return reservation information
		|	return null if no reservation found
		|
		|--------------------------------------------------------------------------
		|
		*/
		return $reservation;
	}

	/**
	*
	*	find the next reservation date
	*	@param date
	*	@return carbon formatted reservation date
	*
	*/
	public static function thirdWorkingDay($date)
	{

		$curdate = Carbon::parse($date);
		$date = Carbon::parse($date);
		$work_days = 0;

		/*
		|--------------------------------------------------------------------------
		|
		| 	this is for checking 
		|	that the date is three days
		|	after the current date
		|
		|--------------------------------------------------------------------------
		|
		*/
		$three_day_rule = 0;
		$date_counter = 0;

		do
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	get the next date
			|
			|--------------------------------------------------------------------------
			|
			*/
			$_date = $date->addDays($date_counter);
			/*
			|--------------------------------------------------------------------------
			|
			| 	check if date is available for reservation
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(SpecialEvent::isAvailable($_date))
			{

				/*
				|--------------------------------------------------------------------------
				|
				| 	if the date is available
				|	add 1 to working days
				|	add 1 to three day rule
				|
				|--------------------------------------------------------------------------
				|
				*/
				$work_days++;
				$three_day_rule++;
			}

			$date_counter++;

		} while ( $three_day_rule < 3 );

		return $curdate->addDays($work_days);
	}
}
