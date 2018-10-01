<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
	
	protected $table  = 'receipts';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = [
		'number', 'purchaseorder_number', 'purchaseorder_date', 'invoice_number', 'invoice_date', 'fund_code',
	];

	//Validation rules!
	// public function rules(){
	// 	return	array(
	// 		'Property Acknowledgement Receipt' => 'required|min:2|max:25',
	// 		'Purchase Order Number' => 'required|min:2|max:25',
	// 		'Purchase Order Date' => 'required|min:2|max:25|date',
	// 		'Invoice Number' => 'required|min:2|max:25',
	// 		'Invoice Date' => 'required|min:2|max:25|date',
	// 		'Fund Code' => 'min:2|max:25'
	// 	);
	// }
	// public function updateRules(){
	// 	return	array(
	// 		'Property Acknowledgement Receipt' => 'min:2|max:25',
	// 		'Purchase Order Number' => 'min:2|max:25',
	// 		'Purchase Order Date' => 'min:2|max:25|date',
	// 		'Invoice Number' => 'min:2|max:25',
	// 		'Invoice Date' => 'min:2|max:25|date',
	// 		'Fund Code' => 'min:2|max:25'
	// 	);
	// }

	// public function inventoryRules(){
	// 	return	array(
	// 		'Receipt Number' => 'required|min:2|max:25',
	// 	);

	// }

	// public function scopeFindByNumber($query, $value)
	// {
	// 	return $query->where('number', '=', $value)->first();
	// }

	// public function inventory()
	// {
	//     return $this->belongsToMany('App\Inventory', 'inventory_receipt', 'receipt_id', 'inventory_id')
	//             ->withPivot('received_quantity', 'received_unitcost', 'profiled_items')
	//             ->withTimestamps();
	// }

	// public function scopeFindThroughInventory($query, $id)
	// {
	// 	$query->whereHas('inventory', function ($innerQuery) use($id) {
	// 		$innerQuery->where('id', '=', $id);	
	// 	});
	// }


}
