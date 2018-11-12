<?php

namespace App\Models\Workstation;

use App\Models\Room\Room;
use App\Models\Item\Item;
use App\Models\Ticket\Ticket;
use App\Models\Software\Software;
use App\Http\Modules\Generator\Code;
use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\Generator\ListGenerator;

class Workstation extends Model
{
	const WORKING_STATUS = 'working';
	const UNDERMAINTENANCE_STATUS = 'undermaintenance';

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
		'system_unit_local', 'monitor_local', 'keyboard_local', 'avr_local', 'mouse_local', 'location', 
		'status'
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
	 * Returns the status of the workstation
	 *
	 * @return void
	 */
	public function getStatusAttribute()
	{
		return ucfirst($this->isUnderMaintenance() ? self::UNDERMAINTENANCE_STATUS : self::WORKING_STATUS);
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
	 * Filters by workstation name
	 *
	 * @param Builder $query
	 * @param string $value
	 * @return object
	 */
	public function scopeName($query, $value)
	{
		return $query->where('name', '=', $value);
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

	/**
	 * Set the status based on the given value
	 *
	 * @param bool $status
	 * @return object
	 */
	public function maintenance(bool $status)
	{
		// loops through each part of the workstation
		foreach($this->parts() as $part) {

			// checks if that part exists
			if(isset($part)) {
				$part->maintenance($status);
			} 
		}

		return $this;
	}

	/**
	 * Returns the parts of the current workstation
	 *
	 * @return collection
	 */
	public function parts()
	{
		
		$collection = collect([
			'system unit' => $this->systemunit,
			'monitor' => $this->monitor,
			'mouse' => $this->mouse,
			'keyboard' => $this->keyboard,
			'avr' => $this->avr,
		]);

		return $collection;
	}

	/**
	 * Checks if the workstation is under maintenance
	 * returns true else returns false if not
	 *
	 * @return boolean
	 */
	public function isUnderMaintenance()
	{
		// loops through each part of the workstation
		foreach($this->parts() as $part) {

			// checks if that part exists
			if(isset($part) && $part->isUnderMaintenance()) {
				return true;
			} 
		}

		return false;
	}

	/**
	 * Generates workstation name from the given room
	 *
	 * @return void
	 */
	public function generateName($roomName = null)
	{

		// generate a code for the workstation name
		// use a custom package designed specifically to
		// generate a code
		$code = Code::make([
			config('app.workstation_id'),
			isset($roomName) ? $roomName : 'TMP',
			Workstation::count() + 1,
		], Code::DASH_SEPARATOR);

		return $code;
	}

    
}
