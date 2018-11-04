<?php

namespace App\Models\Item;

use Carbon\Carbon;
use App\Models\Room\Room;
use App\Models\Item\Type;
use App\Models\Ticket\Ticket;
use App\Models\Inventory\Inventory;
use App\Http\Modules\Generator\Code;
use Illuminate\Database\Eloquent\Model;
use App\Models\Workstation\Workstation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Modules\Generator\ListGenerator;

class Item extends Model
{
	use SoftDeletes;

	const WORKING_STATUS = 'working';
	const CONDEMN_STATUS = 'condemn';
	
	protected $table = 'items';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $fillable = [
		'local_id', 'property_number', 'serial_number', 'location', 'date_received', 'status',
		'inventory_id', 'receipt_id', 'profiled_by', 'warranty', 'lent_at', 'lent_by', 'deployed_at',
		'deployed_by', 'for_reservation'
	];
	
	// public static $rules = array(
	// 	'University Property Number' => 'min:5|max:100|unique:items,local_id',
	// 	'Property Number' => 'min:5|max:100|unique:items,property_number',
	// 	'Serial Number' => 'required|min:5|max:100|unique:items,serial_number',
	// 	'Location' =>'required',
	// 	'Date Received' =>'required|date',
	// 	'Status' =>'required|min:5|max:50'

	// );
	
	// public static $updateRules = array(
	// 	'Property Number' => 'min:5|max:100',
	// 	'Serial Number' => 'min:5|max:100',
	// 	'Location' =>'',
	// 	'Date Received' =>'date',
	// 	'Status' =>'min:5|max:50'
	// );

	// public static $updateForReservationRules = array(
	// 	'id' => 'required|exists:items,id',
	// 	'checked' => 'required|boolean',
	// );

	public function scopeAllowedOnReservation($query)
	{
		return $query->where('for_reservation', '=', 1);
	}

	// public function scopeDisabledReservation($query)
	// {
	// 	$query->where('for_reservation', '=', 0);
	// }

	// public function disableReservation()
	// {
	// 	$this->for_reservation = 0;
	// 	$this->save();

	// 	return $this;
	// }

	// public function enableReservation()
	// {
	// 	$this->for_reservation = 1;
	// 	$this->save();

	// 	return $this;
	// }

	public static $category = [
		'equipment',
		'fixtures',
		'furniture',
		'supplies'
	];

	protected $appends = [
		'parsed_date_received', 'parsed_date_profiled', 'reservation_status',
		'descriptive_name'
	];

	/**
	 * Return parsed name for the name using the brand, model and type
	 *
	 * @return void
	 */
	public function getDescriptiveNameAttribute()
	{
		$brand = $this->inventory->brand;
		$model = $this->inventory->model;
		$type = $this->inventory->type->name;

		return $brand . '-' . $model . '-' . $type;
		
	}

	/**
	 * Returns the status if the item is available for reservation
	 *
	 * @return void
	 */
	public function getReservationStatusAttribute()
	{
		return ($this->for_reservation) ? "Yes" : "No";
	}

	/**
	 * Return human readable date where the item is profiled
	 *
	 * @return void
	 */
	public function getParsedDateReceivedAttribute()
	{
		return Carbon::parse($this->date_received)->toFormattedDateString();
	}

	/**
	 * Return human readable date format
	 *
	 * @return void
	 */
	public function getParsedDateProfiledAttribute()
	{
		return Carbon::parse($this->created_at)->toFormattedDateString();
	}

	/**
	 * References inventory table
	 *
	 * @return void
	 */
	public function inventory()
	{
		return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
	}

	public function type()
	{
		return $this->hasManyThrough(
			Inventory::class, 
			Type::class,
			'id',
			'itemtype_id'
		);
	}

	/*
	*
	*	Foreign key referencing receipt table
	*
	*/
	// public function receipt()
	// {
	// 	return $this->belongsTo('App\Receipt','receipt_id','id');
	// }

	/**
	 * References rooms table
	 * 
	 * @return object
	 */
	public function room()
	{
		return $this->belongsTo(Room::class, 'location', 'id');
	}


	/**
	 * References tickets table
	 *
	 * @return void
	 */
	public function tickets()
	{
		return $this->belongsToMany(Ticket::class,'item_ticket','item_id','ticket_id');
	}

	/**
	 * Filters the result by local id
	 *
	 * @param Builder $query
	 * @param int $id
	 * @return object
	 */
	public function scopeLocalId($query, $id)
	{
		return $query->where('local_id', '=', $id);
	}

	/**
	 * Filters the result by property number
	 *
	 * @param Builder $query
	 * @param int $propertyNumber
	 * @return object
	 */
	public function scopePropertyNumber($query, $propertyNumber)
	{
		return $query->where('property_number', '=', $propertyNumber);
	}

	/**
	 * Filters the result by local ids
	 *
	 * @param Builder $query
	 * @param int $propertyNumber
	 * @return object
	 */
	public function scopeInLocalIds($query, array $local_ids)
	{
		return $query->whereIn('local_id',  $local_ids);
	}

	/**
	 * Filters the current search result by type name
	 *
	 * @param Builder $query
	 * @param int $id
	 * @return object
	 */
	public function scopeNameOfType($query, string $name)
	{
		return $query->whereHas('inventory', function($query) use ($name) {
			$query->whereHas('type', function($query) use ($name) {
				$query->name($name);
			});
		});
	}

	/**
	 * Filters the current search result by type name
	 * in the array provided
	 *
	 * @param Builder $query
	 * @param int $arrayValues
	 * @return object
	 */
	public function scopeNameOfTypeIn($query, array $arrayValues)
	{
		return $query->whereHas('inventory', function($query) use ($arrayValues) {
			$query->whereHas('type', function($query) use ($arrayValues) {
				$query->nameIn($arrayValues);
			});
		});
	}

	/**
	 * Filters the current search result by id
	 *
	 * @param Builder $query
	 * @param int $id
	 * @return object
	 */
	public function scopeFilterByTypeId($query, int $id)
	{
		return $query->whereHas('inventory', function($query) use ($id) {
			$query->where('itemtype_id', '=', $id);
		});
	}

	/**
	 * Returns list where reservation is allowed
	 *
	 * @param Builder $query
	 * @return object
	 */
	public function scopeAuthorizedOnReservation($query)
	{
		return $query->where('for_reservation', '=', true);
	}

	/**
	 * Filter the query by items not yet assembled in workstation
	 *
	 * @param Builder $query
	 * @return object
	 */
	public function scopeNotAssembledInWorkstation($query)
	{
		$query->whereNotIn('id', ListGenerator::makeArray(
			Workstation::pluck('systemunit_id')->toArray(),
			Workstation::pluck('monitor_id')->toArray(),
			Workstation::pluck('keyboard_id')->toArray(), 
			Workstation::pluck('mouse_id')->toArray(),
			Workstation::pluck('avr_id')->toArray()
		)->unique());
	}
	
	/**
	 * Generate code based on the format given
	 *
	 * @param Builder $inventory
	 * @param integer $increments
	 * @param integer $itemCount
	 * @return string
	 */
	public function generateCode($inventory, $increments = 1, $itemCount = null)
	{
		$type = $inventory->itemtype_id;
		$lastItem = Item::filterByTypeId($type)->orderBy('id', 'desc')->first()->local_id;
		$array = explode(
			Code::DASH_SEPARATOR, 
			$lastItem
		);

		$lastIdOfTheItem = array_values(array_slice($array, -1))[0];
		$itemCount = $itemCount ? $itemCount : $lastIdOfTheItem;

		// generate a code for the workstation name
		// use a custom package designed specifically to
		// generate a code
		return Code::make([
			config('app.local_id'),
			$type,
			$itemCount + $increments,
		], Code::DASH_SEPARATOR);
	}

	/**
	 * Returns the status for working
	 *
	 * @return string
	 */
	public function getWorkingStatus()
	{
		return self::WORKING_STATUS;
	}

	/**
	 * Returns the status for condemn
	 *
	 * @return string
	 */
	public function getCondemnStatus()
	{
		return self::CONDEMN_STATUS;
	}
}
