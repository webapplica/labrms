<?php

namespace App\Models\Item;

use Carbon\Carbon;
use App\Models\Receipt;
use App\Models\Room\Room;
use App\Models\Item\Type;
use App\Models\Ticket\Ticket;
use App\Models\Inventory\Inventory;
use App\Http\Modules\Generator\Code;
use App\Models\Reservation\Reservation;
use Illuminate\Database\Eloquent\Model;
use App\Models\Workstation\Workstation;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Modules\Generator\ListGenerator;

class Item extends Model
{
	use SoftDeletes;

	const WORKING_STATUS = 'working';
	const CONDEMN_STATUS = 'condemn';
	const UNDERMAINTENANCE_STATUS = 'undermaintenance';
	const ALLOWED_ON_RESERVATION = 1;
	const DISABLED_ON_RESERVATION = 1;
	
	protected $table = 'items';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	public $fillable = [
		'local_id', 'property_number', 'serial_number', 'location', 'date_received', 'status',
		'inventory_id', 'receipt_id', 'profiled_by', 'warranty', 'lent_at', 'lent_by', 'deployed_at',
		'deployed_by', 'for_reservation'
	];
	
	/**
	 * Different category types for the item
	 *
	 * @var array
	 */
	public static $category = [
		'equipment',
		'fixtures',
		'furniture',
		'supplies'
	];

	/**
	 * Added fields on select query
	 *
	 * @var array
	 */
	protected $appends = [
		'parsed_date_received', 'parsed_date_profiled', 'reservation_status',
		'descriptive_name'
	];
	
	/**
	 * Filters the query where the reservation status
	 * is allowed
	 *
	 * @param Builder $query
	 * @return object
	 */
	public function scopeAllowedOnReservation($query)
	{
		return $query->where('for_reservation', '=', self::ALLOWED_ON_RESERVATION);
	}

	/**
	 * Filters the query where the reservation status
	 * is not on
	 *
	 * @param Builder $query
	 * @return object
	 */
	public function scopeDisabledReservation($query)
	{
		$query->where('for_reservation', '=', self::DISABLED_ON_RESERVATION);
	}

	/**
	 * Filters the query by inventory id
	 *
	 * @param Builder $query
	 * @param integer|array $id
	 * @return object
	 */
	public function scopeInInventory($query, $id)
	{

		// if the id attribute is array
		// use where in query to filter
		// the result of the query
		if(is_array($id)) {
			return $query->whereIn('inventory_id', $id);
		}

		// use the default where to filter
		// the result of the query
		return $query->where('inventory_id', '=', $id);
	}

	/**
	 * Filters the result where items is reserved
	 * on the start and date supplied
	 *
	 * @param Builder $query
	 * @param datetime $start
	 * @param datetime $end
	 * @return object
	 */
	public function scopeReservedOn($query, $start, $end)
	{
		return $query->whereHas('reservation', function($query) use ($start, $end) {
			$query->whereBetween('start', [ $start, $end ])
				->orWhereBetween('end', [ $start, $end ]);
		});
	}

	/**
	 * Filters the result where items is not yet reserved
	 * on the start and date supplied
	 *
	 * @param Builder $query
	 * @param datetime $start
	 * @param datetime $end
	 * @return object
	 */
	public function scopeNotReservedOn($query, $start, $end)
	{
		$items = Item::with('reservation')
					->reservedOn($start, $end)
					->pluck('id')
					->toArray();
					
		return $query->whereNotIn('id', $items);
	}

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
	 * References reservation table
	 *
	 * @return void
	 */
	public function reservation()
	{
		return $this->belongsToMany(
			Reservation::class, 'item_reservation', 'reservation_id', 'item_id'
		);
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

	/**
	 * References type table connected from
	 * the inventory table
	 *
	 * @return object
	 */
	public function type()
	{
		return $this->hasManyThrough(
			Inventory::class, Type::class, 'id', 'itemtype_id'
		);
	}
	
	/**
	 * References receipt table
	 *
	 * @return object
	 */
	public function receipt()
	{
		return $this->belongsTo(Receipt::class,'receipt_id', 'id');
	}

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

		// checks if the itemCount variable is initialized
		// if its not initialize, use the last number from the
		// last item profiled
		if(! isset($itemCount)) {

			// fetch the last item from the items table
			$lastItem = Item::filterByTypeId($type)->orderBy('id', 'desc')->first();

			// checks if the item table has a an item
			// if there is an item, fetch the last row 
			// in the converted array from the local id
			if(count($lastItem) > 0) {
				$lastItem = $lastItem->local_id;	

				// converts the string in to array using
				// the dash as separator
				$array = explode(
					Code::DASH_SEPARATOR, 
					$lastItem
				);
				
				// fetch the last value in the array
				$itemCount = array_values(array_slice($array, -1))[0];
			} 

			// checks if the item table has a an item
			// if there is no item, returns 0
			else {
				$itemCount = 0;
			}
		}

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

	/**
	 * Returns the status for undermaintenance
	 *
	 * @return string
	 */
	public function getUnderMaintenanceStatus()
	{
		return self::UNDERMAINTENANCE_STATUS;
	}

	/**
	 * Sets the status to undermaintenance if the value passed is
	 * true else set the status to working
	 *
	 * @param boolean $status
	 * @return void
	 */
	public function maintenance($status = true)
	{
		$this->status = $status ? self::UNDERMAINTENANCE_STATUS : self::WORKING_STATUS;
		$this->save();

		return $this;
	}

	/**
	 * Checks if the status for the item is
	 * under maintenance
	 *
	 * @return boolean
	 */
	public function isUnderMaintenance()
	{
		return $this->status == self::UNDERMAINTENANCE_STATUS;
	}
}
