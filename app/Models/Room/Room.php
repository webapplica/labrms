<?php

namespace App\Models\Room;

use App\Models\Room\Category;
use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	protected $table = 'rooms';
	protected $primaryKey = 'id';
	public $fillable = ['name', 'description', 'status', 'is_default'];
	public $timestamps = false;

	/**
	 * filter the query by the name of location
	 * 
	 * @param  object $query    
	 * @param  string $location
	 * @return 
	 */
	public function scopeName($query, $name)
	{
		return $query->where('name', '=', $name);
	}

	/**
	 * relationship to ticket table
	 * 
	 * @return
	 */
	public function tickets()
	{
		return $this->belongsToMany(Ticket::class, 'room_ticket', 'room_id', 'ticket_id');
	}

	/**
	 * relationship to categories table
	 * 
	 * @return
	 */
	public function categories()
	{
		return $this->belongsToMany(Category::class, 'room_category', 'room_id', 'category_id');
	}

	/**
	 * Get a column from categories
	 *
	 * @param string $column
	 * @return void
	 */
	public function includeCategoriesOnColumn($column)
	{
		return $this->categories->pluck($column);
	}

}
