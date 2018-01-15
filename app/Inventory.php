<?php

namespace App;

use Carbon\Carbon;
use DB;
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

  public function items()
  {
    return $this->hasMany('App\Item','inventory_id','id');
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

  public function scopeBrand($query,$brand)
  {
    return $query->where('brand','=',$brand);
  }

  public function scopeModel($query,$model)
  {
    return $query->where('model','=',$model);
  }

  public function itemtype()
  {
    return $this->belongsTo('App\ItemType','itemtype_id','id');
  }

  public function itemsubtype()
  {
    return $this->belongsTo('App\ItemSubType','itemsubtype_id','id');
  }

  public function receipts()
  {
    return $this->belongsToMany('App\Receipt', 'inventory_receipt', 'inventory_id', 'receipt_id');
  }

  /**
  *
  * increment profiled items
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function addProfiled($inventory_id)
  {
		$inventory = Inventory::find($inventory_id);
		$inventory->profileditems = $inventory->profileditems + 1;
		$inventory->save();
  }


  /**
  *
  * decrement profiled items
  * decreases quantity
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  public static function removeProfiled($inventory_id)
  {
		$inventory = Inventory::find($inventory_id);
		$inventory->quantity = $inventory->quantity - 1;
		$inventory->profileditems = $inventory->profileditems - 1;
		$inventory->save();
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
    DB::transaction(function() use($id){
  		$itemprofile = ItemProfile::findOrFail($id);

      /*
      |--------------------------------------------------------------------------
      |
      |   Calls removeProfiled function
      |
      |--------------------------------------------------------------------------
      |
      */
  		Inventory::removeProfiled($itemprofile->inventory_id);
      Ticket::condemnTicket($itemprofile->propertynumber);
  		$itemprofile->delete();
    });
  }

}
