<?php

namespace App\Models\Room;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
	protected $table = 'rooms';
	protected $primaryKey = 'id';
	public $fillable = ['name','category','description','status', 'is_default'];
	public $timestamps = false;

	public function rules()
	{
		return [
			'Name' => 'required|min:4|max:100|unique:rooms,name' ,
			'Description' => 'min:4',
			'Category' => 'exists:room_categories,id'
		];
	}

	public function updateRules()
	{
		$name = $this->name;
		
		return array(
			'Name' => 'required|min:4|max:100|unique:rooms,name,' . $name . ',name' ,
			'Description' => 'min:4'
		);
	}

	public function scopeLocation($query, $location)
	{
		return $query->where('name', '=', $location);
	}

	public function scopeFindByLocation($query, $location)
	{
		return $query->where('name', '=', $location);
	}

	public function tickets()
	{
		return $this->belongsToMany('App\Ticket','room_ticket','room_id','ticket_id');
	}

	public function categories()
	{
		return $this->belongsToMany('App\RoomCategory', 'room_category', 'room_id', 'category_id');
	}

}