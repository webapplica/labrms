<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';
	public $timestamps = true;

	public $fillable = ['unit'];
	protected $primaryKey = 'id';

	public static $rules = array(
		'Name' => 'required|unique:unit,name',
		'Description' => ''
	);

	public static $updateRules = array(
		'Name' => 'required',
		'Description' => ''
	);

}
