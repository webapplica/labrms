<?php

namespace App\Models\Inventory;

// use DB;
// use Auth;
// use App\Ticket;
// use Carbon\Carbon;
// use App\ItemProfile;
use App\Models\Receipt;
use App\Models\Item\Item;
use App\Models\Item\Type;
use App\Models\Inventory\Log;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{

    protected $table = 'inventories';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $fillable = ['code', 'itemtype_id', 'brand', 'model', 'details', 'unit_name', 'user_id'];

  // public static $rules = [
  // 	'Item Type' => 'required|exists:item_types,id',
  // 	'Brand' => 'min:2|max:100',
  // 	'Model' => 'min:2|max:100',
  // 	'Details' => 'min:5|max:1000',
  // 	'Unit' => 'required',
  // 	'Quantity' => 'required|numeric|min:1',
  // 	'Profiled Items' => 'numeric'

  // ];

	// public static $updateRules = [
	// 	'Item Type' => 'required|min:5|max:100',
	// 	'Brand' => 'min:2|max:100',
	// 	'Model' => 'min:2|max:100',
	// 	'Details' => 'min:5|max:1000',
	// 	'Unit' => 'numeric',
  // 	'Quantity' => 'required|numeric|min:0',
	// 	'Profiled Items' => 'numeric'
	// ];

  // public static $releaseRules = [
  //   'purpose' => 'required|min:5|max:150',
  //   'quantity' => 'required|integer',
  //   'inventory' => 'required|exists:inventories,id'
  // ];

    protected $appends = [
        'quantity', 'unprofiled', 'item_type_name', 'summarized_name',
    ];

    /**
     * Returns count for all quantity
     *
     * @return void
     */
    public function getQuantityAttribute()
    {
        $log = isset($this->logs) ? $this->logs->sum('quantity') : 0;
        $receipts = isset($this->receipts) ? $this->receipts->sum('pivot.profiled_items') : 0;

        return  $log + $receipts;
    }

    /**
     * Returns count for unprofiled items
     *
     * @return void
     */
    public function getUnprofiledAttribute()
    {
        return $this->logs->sum('quantity');
    }

    /**
     * Returns the current linked name of the type
     *
     * @return void
     */
    public function getItemTypeNameAttribute()
    {
        $name = isset($this->type) ? $this->type->name : 'None';
        return $name;
    }

    /**
     * Returns the summary of inventory information
     *
     * @return void
     */
    public function getSummarizedNameAttribute()
    {
        return $this->brand . '-' . $this->model . '-' . $this->item_type_name;
    }

  // public function getBrandAttribute($value)
  // {
  //   return ucwords($value);
  // }

  // public function getModelAttribute($value)
  // {
  //   return ucwords($value);
  // }

  // public function getWarrantyAttribute($value)
  // {
  //   return ucwords($value);
  // }

  // public function scopeType($query,$id)
  // {
  //   return $query->where('itemtype_id','=',$id);
  // }

  // public function scopeLocate($query, $brand, $model, $itemtype)
  // {
  //   return $query->where('brand', '=', $brand)
  //               ->where('model', '=', $model)
  //               ->where('itemtype_id', '=', $itemtype);
  // }

  // public function scopeBrand($query,$brand)
  // {
  //   return $query->where('brand','=',$brand);
  // }

  // public function scopeModel($query,$model)
  // {
  //   return $query->where('model','=',$model);
  // }

    /**
     * Returns relationship on items model
     *
     * @return void
     */
    public function items()
    {
        return $this->hasMany(Item::class,'inventory_id','id');
    }

    /**
     * Returns relationship on type model
     *
     * @return void
     */
    public function type()
    {
        return $this->belongsTo(Type::class,'itemtype_id','id');
    }

  // public function unit()
  // {
  //   return $this->belongsTo('App\Unit','unit_name','name');
  // }

    /**
     * Returns relationship on inventory transactions
     *
     * @return void
     */
    public function logs()
    {
        return $this->hasMany(Log::class, 'inventory_id', 'id');
    }

    /**
     * Returns relationship on receipts table
     *
     * @return void
     */
    public function receipts()
    {
        return $this->belongsToMany(Receipt::class, 'inventory_receipt', 'inventory_id', 'receipt_id')
            ->withPivot('received_quantity', 'received_unitcost', 'profiled_items')
            ->withTimestamps();
    }

    /**
     * Generate code for inventory
     *
     * @return string
     */
    public static function generateCode()
    {
        $inventory_count = Inventory::pluck('id')->count() + 1;
        $code = 'INV' . str_pad($inventory_count, 6, '0', STR_PAD_LEFT);
        
        return $code;
    }

  /**
  *
  * increment profiled items
  * 
  * @param $inventory_id accepts id
  * validate before using this function
  *
  */
  // public static function addProfiled($inventory_id, $receipt_id)
  // {
	// 	$inventory = Inventory::find($inventory_id);
	// 	$inventory = $inventory->receipts()->find($receipt_id);
  //   $inventory->pivot->profiled_items = $inventory->pivot->profiled_items + 1;
	// 	$inventory->pivot->save();
  // }

  // public function log($quantity, $details)
  // {
  //   $total = InventoryLog::findByInventoryID($this->id)->sum('quantity')  + $quantity ;
    
  //   $log = new InventoryLog;
  //   $log->inventory_id = $this->id;
  //   $log->quantity = $quantity;
  //   $log->details = $details;
  //   $log->remaining_balance = $total;
  //   $log->save();
  // }

  // /**
  // *
  // * calls remove profiled
  // * @param $inventory_id accepts id
  // * validate before using this function
  // *
  // */
  // public static function condemn($id)
  // {
  //   DB::beginTransaction();

	// 	$itemprofile = Item::findOrFail($id);
    
  //   $ticket = new Ticket;
  //   $ticket->condemn($itemprofile->propertynumber);

	// 	$itemprofile->delete();

  //   DB::commit();
  // }

}
