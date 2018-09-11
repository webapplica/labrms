<?php

namespace App\Models\Items;

use DB;
use Illuminate\Database\Eloquent\Model;

class Type extends Model 
{

	protected $table = 'item_types';
	protected $primaryKey = 'id';
	public $timestamps = true;

	public $fillable = [
		'name',
		'description',
		'category'
	];

	public function rules() 
	{
		return [
			'name' => 'required|min:2|max:50|unique:item_types,name',
			'description' => 'min:5|max:450'
		];
	}

	public function updateRules()
	{
		return [
			'name' => 'required|min:2|max:50|unique:item_types,name,'. $this->name .',name',
			'description' => 'min:5|max:450'
		];
	}

	/**
	*
	* exist in table rules
	*
	*/
	public static $existInTableRules = [
		'id' => 'exists:item_types,id'
	];

	/**
	*
	*	item types category
	*
	*/
	public static $category = [
		'equipment'=>'Equipment',
		'supply' => 'Supply',
		'fixture' => 'Fixture',
		'furniture' => 'Furniture'
	];

	public function setCategoryAttribute($value)
	{
		$this->attributes['category'] = ucfirst($value);
	}

	public function getCategoryAttribute($value)
	{
		if( isset($value) || $value !== "" || $value !== null ) {
			return ucfirst($value);
		}
		else {
			return "None";
		}
	}

	public function items()
	{
		return $this->hasManyThrough('App\Items','App\Inventory','id','id');
	}

	/**
	*
	*	@param $type accepts the type name
	*	usage: ItemType::findByType('System Unit')->get();
	*
	*/
	public function scopeFindByType($query, $value)
	{
		return $query->where('name','=', $value);
	}

	/**
	*
	*	@param $type accepts category
	*	usage: ItemType::category('System Unit')->get();
	*
	*/
  public function scopeCategory($query,$category)
  {
  	return $query->where('category','=',$category);
  }
}
