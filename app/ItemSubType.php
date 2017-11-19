<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemSubType extends Model
{
    protected $table = 'itemsubtype';
	protected $primaryKey = 'id';
	public $fillable = ['name'];
	public static $rules = array(
		'Name' => 'required|string|min:2|max:100'
	);

	public static $updateRules = array(
		'Name' => 'string|min:2|max:100'
	);
}