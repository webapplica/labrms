<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';
	public $timestamps = true;

	public $fillable = ['units'];
	protected $primaryKey = 'id';

	public static $rules = array(
		'Name' => 'required|unique:units,name',
		'Description' => ''
	);

	public static $updateRules = array(
		'Name' => 'required',
		'Description' => ''
	);

	public function scopeFindByName($query, $value)
	{
		return $query->where('name', '=', $value);
	}

	public function scopeFindByAbbreviation($query, $value)
	{
		return $query->where('abbreviation', '=', $value);
	}

}
