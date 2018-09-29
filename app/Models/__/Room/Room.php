<?php

namespace App\Models\Room;

use App\Models\Room\Category;
use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	protected $table = 'rooms';
	protected $primaryKey = 'id';
	public $fillable = ['name','category','description','status', 'is_default'];
	public $timestamps = false;

	/**
	 * [scopeLocation description]
	 * 
	 * @param  [type] $query    [description]
	 * @param  [type] $location [description]
	 * @return [type]           [description]
	 */
	public function scopeLocation($query, $location)
	{
		return $query->where('name', '=', $location);
	}

	/**
	 * [scopeFindByLocation description]
	 * 
	 * @param  [type] $query    [description]
	 * @param  [type] $location [description]
	 * @return [type]           [description]
	 */
	public function scopeFindByLocation($query, $location)
	{
		return $query->where('name', '=', $location);
	}

	/**
	 * [tickets description]
	 * 
	 * @return [type] [description]
	 */
	public function tickets()
	{
		return $this->belongsToMany(Ticket::class, 'room_ticket', 'room_id', 'ticket_id');
	}

	/**
	 * [categories description]
	 * 
	 * @return [type] [description]
	 */
	public function categories()
	{
		return $this->belongsToMany(Category::class, 'room_category', 'room_id', 'category_id');
	}

}
