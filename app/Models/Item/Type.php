<?php

namespace App\Models\Item;

// use App\Models\Item;
// use App\Models\Inventory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model 
{

	protected $table = 'item_types';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $fillable = [ 'name', 'description', 'category' ];

	/**
	 * Categories for items
	 * 
	 * @var $categories
	 */
	// private static $categories = [
	// 	'equipment' => 'Equipment',
	// 	'supply' => 'Supply',
	// 	'fixture' => 'Fixture',
	// 	'furniture' => 'Furniture'
	// ];

	/**
	 * Sets the fetched category to uppercase first letter
	 * 
	 * @param $value
	 */
	// public function setCategoryAttribute($value)
	// {
	// 	$this->attributes['category'] = ucfirst($value);
	// }

	/**
	 * Returns none if the item has no category
	 * 
	 * @param  [string] $value 
	 * @return string
	 */
	// public function getCategoryAttribute($value)
	// {
	// 	if( isset($value) || $value !== "" || $value !== null ) {
	// 		return "None";
	// 	}

	// 	return ucfirst($value);
	// }

	/**
	 * Returns list of categories the item have
	 * 
	 * @return array
	 */
	// public function categories()
	// {
	// 	return self::$categories;
	// }

	/**
	 * returns list of items the type linked to
	 * 
	 * @return object
	 */
	// public function items()
	// {
	// 	return $this->hasManyThrough( Item::class, Inventory::class, 'id', 'id');
	// }

	/**
	 * filters search result by the type with the name provided
	 * 
	 * @param  object $query 
	 * @param  string $value 
	 * @return object
	 */
	// public function scopeName($query, $value)
	// {
	// 	return $query->where('name', '=', $value);
	// }

	/**
	 * filters search result by the type with the category provided
	 * 
	 * @param  object $query 
	 * @param  string $value 
	 * @return object
	 */
	// public function scopeCategory($query,$category)
	// {
	// 	return $query->where('category', '=', $category);
	// }
}
