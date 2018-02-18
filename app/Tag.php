<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
	public $timestamps = true;

	public $fillable = ['name'];
	protected $primaryKey = 'id';

	public static $rules = array(
		'Name' => 'required|unique:units,name'
	);

	public static $updateRules = array(
		'Name' => 'required'
	);

	public function scopeFindByName($query, $value)
	{
		return $query->where('name', '=', $value);
	}

}
