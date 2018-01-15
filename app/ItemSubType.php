<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSubType extends Model
{
    protected $table = 'item_subtypes';
	protected $primaryKey = 'id';
	public $fillable = ['name'];
	public static $rules = array(
		'Name' => 'required|string|min:2|max:100',
		'Item Type' => 'required|exists:itemtype,id',
	);

	public static $updateRules = array(
		'Name' => 'string|min:2|max:100',
		'Item Type' => 'required|exists:itemtype,id',
	);

	public function itemtype()
	{
		return $this->belongsTo("App\ItemType",'itemtype_id','id');
	}
}