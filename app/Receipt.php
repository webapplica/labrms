<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Receipt extends \Eloquent{
	
	protected $table  = 'receipts';
	protected $primaryKey = 'id';

	public $timestamps = false;

	protected $fillable = ['number','purchaseorder_number','purchaseorder_date','invoice_number','invoice_date','fund_code'];

	//Validation rules!
	public function rules(){
		return	array(
			'Property Acknowledgement Receipt' => 'required|min:2|max:25',
			'Purchase Order Number' => 'required|min:2|max:25',
			'Purchase Order Date' => 'required|min:2|max:25|date',
			'Invoice Number' => 'required|min:2|max:25',
			'Invoice Date' => 'required|min:2|max:25|date',
			'Fund Code' => 'min:2|max:25'
		);
	}
	public function updateRules(){
		return	array(
			'Property Acknowledgement Receipt' => 'min:2|max:25',
			'Purchase Order Number' => 'min:2|max:25',
			'Purchase Order Date' => 'min:2|max:25|date',
			'Invoice Number' => 'min:2|max:25',
			'Invoice Date' => 'min:2|max:25|date',
			'Fund Code' => 'min:2|max:25'
		);
	}


}
