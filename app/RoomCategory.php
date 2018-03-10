<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomCategory extends Model
{
    protected $table = 'room_categories';
	protected $primaryKey = 'id';
	public $timestamps = true;
	public $incrementing = true;
	public $fillable = ['name'];

	public function rules()
	{
		return array(
			'Category Name' => 'required|string|min:5|max:100'
		);
	}

	public function updateRules()
	{
		$name = $this->name;
		return array(
			'Category' => 'required|exists:room_categories,id',
			'Category Name' => 'required|string|min:5|max:100|unique:room_categories,name,' . $name . ',name'
		);
	}

	public function deleteRules()
	{
		return array(
			'Category' => 'required|exists:room_categories,id'
		);
	}
}