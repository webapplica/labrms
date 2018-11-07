<?php

namespace App\Models\Reservation;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Item\Item;
// use App\Models\Events\Special;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{

	const CLAIMED_STATUS = 'claimed';

	protected $table = 'reservations';
	protected $primaryKey = 'id';
	public $timestamps = true;

	protected $dates = [ 
		'timein', 'timeout' 
	];
	
	public $fillable = [
		'purpose', 'user_id', 'faculty_id', 'location', 'start', 'end', 'is_approved','is_disapproved', 'is_claimed', 'is_cancelled', 'accountable', 'reservee'
	];

	// public static $rules = [
	// 	'Items' => 'required',
	// 	'Location' => 'required|exists:rooms,id',
	// 	'Time started' => 'required|date',
	// 	'Time end' => 'required|date',
	// 	'Purpose' => 'required',
	// 	'Faculty-in-charge' => 'nullable|exists:faculties,id'
	// ];

	// public static $roomReservationRules = [
	// 	'Room' => 'required|exists:room,id',
	// 	'Room Name' => 'required|between:4,100',
	// 	'Time started' => 'required|date',
	// 	'Time end' => 'required|date',
	// 	'Purpose' => 'required',
	// 	'Faculty-in-charge' => 'required|between:5,50'
	// ];

	// public static $updateRules = [
	// 	'Location' => 'required|between:4,100',
	// 	'Time started' => 'required|date',
	// 	'Time end' => 'required|date',
	// 	'Purpose' => 'required',
	// 	'Faculty-in-charge' => 'required|between:5,50'
	// ];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}
	
	/**
	 * List all the items from the current reservation
	 *
	 * @return void
	 */
	public function item()
	{
		return $this->belongsToMany(Item::class, 'item_reservation', 'reservation_id', 'item_id'); 
	}

	/**
	 * References room table
	 *
	 * @return void
	 */
	// public function room()
	// {
	// 	return $this->belongsToMany(,'room_reservation','reservation_id','room_id');
	// }
	
	/**
	 * References room table
	 *
	 * @return void
	 */
	public function room()
	{
		return $this->belongsTo(Reservation::class, 'location', 'name');
	}
	
	/**
	 * Additional columns when selecting
	 *
	 * @var array
	 */
	protected $appends = [
		'parsed_date_and_time', 'status_name'
	];

	public function getStatusNameAttribute()
	{
		if( $this->is_disapproved ) {
			return 'disapproved';
		} 
		
		else if( $this->is_cancelled ) {
			return 'cancelled';
		} 
		
		else if( $this->is_claimed ) {
			return 'claimed';
		} 
		
		else if ( $this->is_approved ) { 
			return 'approved';
		} 
		
		else {
			return 'pending';
		}
	}

	/**
	 * Get the users full name attribute
	 *
	 * @return void
	 */
	public function getReserveeNameAttribute()
	{
		return $this->user->full_name;
	}

	/**
	 * Returns a formatted created at
	 *
	 * @return void
	 */
	public function getParsedDateAndTimeAttribute()
	{
		return Carbon::parse($this->end)->format('M d Y h:sA');
	}
	
	/**
	 * Filter unclaimed reservation
	 *
	 * @param Builder $query
	 * @return instance
	 */
	public function scopeUnclaimed($query)
	{
		return $query->where(function($query) {
			$query->whereNull('is_claimed');
		});
	}
	
	/**
	 * Filter approved reservation
	 *
	 * @param Builder $query
	 * @return instance
	 */
	public function scopeApproved($query)
	{
		return $query->whereNotNull('is_approved');
	}

	/**
	 * Filter disapproved reservation
	 *
	 * @param Builder $query
	 * @return instannce
	 */
	public function scopeDisapproved($query)
	{
		return $query->whereNotNull('is_disapproved');
	}

	/**
	 * Filter undecided reservations
	 *
	 * @param Builder $query
	 * @return instannce
	 */
	public function scopeUndecided($query)
	{
		return $query->whereNotNull('is_approved')
				->orWhereNotNull('is_disapproved')
				->orWhereNotNull('is_cancelled');
	}

	// public function scopeWithInfo($query)
	// {
	// 	$query = $query->with('itemprofile.inventory.itemtype')
	// 				->with('user');
	// 	if( Auth::user()->accesslevel == 3 || Auth::user()->accesslevel == 4 )
	// 	{
	// 		$query = $query->where('user_id','=',Auth::user()->id);
	// 	}

	// 	return $query;
					
	// }
	
	/**
	 * Filter the current reservation by the reservee
	 *
	 * @param Builder $query
	 * @param [type] $id
	 * @return instannce
	 */
	public function scopeReservee($query, $id)
	{
		return $query->where('user_id', '=', $id);
	}

	/**
	 * Approve the current reservation instance
	 *
	 * @return instance
	 */
	public static function approve()
	{
		$this->is_approved = Carbon::now();
		$this->save();

		return $this;
	}

	/**
	 * Disapprove the current reservation instance
	 *
	 * @param string $remarks
	 * @return instance
	 */
	public static function disapprove(string $remarks)
	{
		$this->is_disapproved = Carbon::now();
		$this->remarks = $remarks;
		$this->save();
		
		return $this;
	}

	/**
	 * Set current reservation as cancelled
	 *
	 * @return instance
	 */
	public static function cancel($remarks)
	{
		
		$this->is_cancelled = Carbon::now();
		$this->remarks = $remarks;
		$this->save();
		
		return $this;
	}

	/**
	 * Set current reservation status as claimed
	 *
	 * @return instance
	 */
	public static function claim()
	{
		
		$this->remarks = self::CLAIMED_STATUS;
		$this->save();

		return $this;
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
	// public static function hasReserved($start,$end)
	// {
	// 	$reservations = Reservation::whereBetween('timein',[$start->startOfDay(),$start->endOfDay()])
	// 						->approved()
	// 						->get();

	// 	foreach($reservations as $reservation)
	// 	{
	// 		$dateofuse = Carbon::parse($reservation->time_start);

	// 		if(Carbon::parse($start)->isSameDay($dateofuse)) {
	// 			$timein = Carbon::parse($reservation->timein);
	// 			$timeout = Carbon::parse($reservation->timeout);
				
	// 			if(Carbon::parse($start)->between( $timein , $timeout )) {
	// 				return $reservation;
	// 			}
				
	// 			if(Carbon::parse($end)->between( $timein , $timeout )) {
	// 				return $reservation;
	// 			}
				
	// 			if(Carbon::parse($timein)->between( $start , $end )) {
	// 				return $reservation;
	// 			} 

	// 			if(Carbon::parse($timeout)->between( $start , $end )) {
	// 				return $reservation;
	// 			}


	// 		}
	// 	}

	// 	return false;
	// }

	/**
	*
	*	find the next reservation date
	*	@param date
	*	@return carbon formatted reservation date
	*
	*/
	// public static function thirdWorkingDay($date)
	// {

	// 	$curdate = Carbon::parse($date);
	// 	$date = Carbon::parse($date);
	// 	$work_days = 0;
	// 	$three_day_rule = 0;
	// 	$date_counter = 0;

	// 	do {
	// 		$_date = $date->addDays($date_counter);

	// 		if(SpecialEvent::isAvailable($_date)) {

	// 			$work_days++;
	// 			$three_day_rule++;
	// 		}

	// 		$date_counter++;

	// 	} while ( $three_day_rule < 3 );

	// 	return $curdate->addDays($work_days);
	// }
}
