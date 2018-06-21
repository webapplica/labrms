<?php

namespace App;

use Carbon\Carbon;
use DB;
use Auth;
use App\Ticket;
use App\ItemProfile;
use Illuminate\Database\Eloquent\Model;

class Inventory extends \Eloquent
{

  /**
  *
  * table name
  *
  */
  protected $table = 'inventories';

  /**
  *
  * used for create method
  *
  */
  public $fillable = [
    'itemtype_id',
    'brand',
    'model',
    'details',
    'unit',
    'quantity',
    'profileditems'
  ];

  /**
  *
  * created_at and updated_at status
  *
  */
  public $timestamps = true;

  /**
  *
  * The attribute that used as primary key.
  *
  */
  protected $primaryKey = 'id';

  /**
  *
  * validation rules
  *
  */
  public static $rules = array(
  	'Item Type' => 'required|exists:item_types,id',
  	'Brand' => 'min:2|max:100',
  	'Model' => 'min:2|max:100',
  	'Details' => 'min:5|max:1000',
  	'Unit' => 'required',
  	'Quantity' => 'required|numeric',
  	'Profiled Items' => 'numeric'

  );

  /**
  *
  * update rules
  *
  */
	public static $updateRules = array(
		'Item Type' => 'required|min:5|max:100',
		'Brand' => 'min:2|max:100',
		'Model' => 'min:2|max:100',
		'Details' => 'min:5|max:1000',
		'Unit' => 'numeric',
		'Quantity' => 'numeric',
		'Profiled Items' => 'numeric'
	);

  protected $appends = [
    'quantity', 'unprofiled'
  ];

  public static $releaseRules = [
    'purpose' => 'required|min:5|max:150',
    'quantity' => 'required|integer',
    'inventory' => 'required|exists:inventories,id'
  ];

  public function getQuantityAttribute()
  {
    return $this->logs->sum('quantity') + $this->receipts->sum('pivot.profiled_items');;
  }

  public function getUnprofiledAttribute()
  {
    return $this->logs->sum('quantity');
  }

  public function getBrandAttribute($value)
  {
    return ucwords($value);
  }

  public function getModelAttribute($value)
  {
    return ucwords($value);
  }

  public function getWarrantyAttribute($value)
  {
    return ucwords($value);
  }

  public function scopeType($query,$id)
  {
    return $query->where('itemtype_id','=',$id);
  }

  public function scopeLocate($query, $brand, $model, $itemtype)
  {
    return $query->where('brand', '=', $brand)
                ->where('model', '=', $model)
                ->where('itemtype_id', '=', $itemtype);
  }

  public function scopeBrand($query,$brand)
  {
    return $query->where('brand','=',$brand);
  }

  public function scopeModel($query,$model)
  {
    return $query->where('model','=',$model);
  }

  public function items()
  {
    return $this->hasMany('App\Item','inventory_id','id');
  }

  public function itemtype()
  {
    return $this->belongsTo('App\ItemType','itemtype_id','id');
  }

  public function unit()
  {
    return $this->belongsTo('App\Unit','unit_name','name');
  }

  public function logs()
  {
    return $this->hasMany('App\InventoryLog', 'inventory_id', 'id');
  }

  public function receipts()
  {
    return $this->belongsToMany('App\Receipt', 'inventory_receipt', 'inventory_id', 'receipt_id')
            ->withPivot('received_quantity', 'received_unitcost', 'profiled_items')
            ->withTimestamps();
  }

  public static function generateCode()
  {
    $value = Inventory::pluck('id')->count() + 1;
    $code = 'INV' . str_pad($value, 6, '0', STR_PAD_LEFT);
    return $code;
  }

  /**
  *
  * increment profiled items
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function addProfiled($inventory_id, $receipt_id)
  {
		$inventory = Inventory::find($inventory_id);
		$inventory = $inventory->receipts()->find($receipt_id);
    $inventory->pivot->profiled_items = $inventory->pivot->profiled_items + 1;
		$inventory->pivot->save();
  }

  public function log($quantity, $details)
  {
    $total = InventoryLog::findByInventoryID($this->id)->sum('quantity')  + $quantity ;
    
    $log = new InventoryLog;
    $log->inventory_id = $this->id;
    $log->quantity = $quantity;
    $log->details = $details;
    $log->remaining_balance = $total;
    $log->save();
  }

  /**
  *
  * calls remove profiled
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function condemn($id)
  {
    DB::beginTransaction();

		$itemprofile = Item::findOrFail($id);
    
    $ticket = new Ticket;
    $ticket->condemn($itemprofile->propertynumber);

		$itemprofile->delete();

    DB::commit();
  }

}
