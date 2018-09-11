<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationItems extends Model
{

	protected $table = 'reservationitems';
	protected $primaryKey = 'id';
	public $timestamps = false;
	
	public $fillable = [
		'itemtype_id',
		'inventory_id',
		'included',
		'excluded'
	];

	public static $rules = [
		'itemtype' => 'required|exists:itemtype,id',
		'inventory' => 'required|exists:inventory,id'
	];

	public function itemtype()
	{
		return $this->belongsTo('App\ItemType','itemtype_id','id');
	}

	public function inventory()
	{
		return $this->belongsTo('App\Inventory','inventory_id','id');
	}

	public function scopeEnabled($query)
	{
		return $query->where('status','=','Enabled');
	}

	public function scopeDisabled($query)
	{
		return $query->where('status','=','Disabled');
	}
}
