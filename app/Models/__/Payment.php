<?php

namespace App;

// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Payment extends \Eloquent{
	protected $table = 'payments';
	public $timestamps = true;

	public $fillable = ['item_id','ORno','receivedby','details','amount'];
	protected $primaryKey = 'id';
	public static $rules = array(
		'Item id' => 'exists:itemprofile,id|required',
		'OR number' => 'required|string|unique:payment,ORno',
		'Received by' => 'required|alpha',
		'Details' => 'required|alpha',
		'Amount' => 'required|numeric'

	);

	public static $updateRules = array(
		
		'Item id' => 'exists:itemprofile,id|required',
		'OR number' => 'required|string|unique:payment,ORno',
		'Received by' => 'required|alpha',
		'Details' => 'required|alpha',
		'Amount' => 'required|numeric'
	);

	
}