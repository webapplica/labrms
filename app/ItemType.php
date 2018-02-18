<?php

namespace App;

use DB;
// use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ItemType extends \Eloquent{
	// use SoftDeletes;

	/**
	*
	* table name
	*
	*/
	protected $table = 'item_types';

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

	/**
	*
	*	fields to be set as date
	*
	*/
	// protected $dates = ['deleted_at'];

	/**
	*
	* created_at and updated_at status
	*
	*/
	public $timestamps = true;

	/**
	*
	* used for create method
	*
	*/
	public $fillable = [
		'name',
		'description',
		'category'
	];

	/**
	*
	* validation rules
	*
	*/
	public function rules(){
		return array(
			'name' => 'required|min:2|max:50|unique:item_types,name',
			'description' => 'min:5|max:450'
		);
	}

	/**
	*
	* update rules
	*
	*/
	public function updateRules(){
		$name = $this->name;
		return array(
			'name' => 'required|min:2|max:50|unique:item_types,name,'. $name .',name',
			'description' => 'min:5|max:450'
		);
	}

	/**
	*
	* exist in table rules
	*
	*/
	public static $existInTableRules = array(
		'id' => 'exists:item_types,id'
	);

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
		if( isset($value) || $value !== "" || $value !== null )
		{
			return ucfirst($value);
		}
		else
		{
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
