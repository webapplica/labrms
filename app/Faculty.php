<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

use Hash;

class Faculty extends \Eloquent {

	/**
	*
	* table name
	*
	*/	
	protected $table  = 'faculties';

	/**
	*
	* primary key
	*
	*/
	protected $primaryKey = 'id';

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
	protected $fillable = [
		'lastname',
		'firstname',
		'middlename',
		'title',
		'suffix',
		'contactnumber',
		'email',
	];

	/**
	*
	* not shown when querying
	*
	*/  
	protected $hidden = ['password','remember_token'];

	/**
	*
	* validation rules
	*
	*/
	public static $rules = array(
		'First name' => 'required|between:2,100|string',
		'Middle name' => 'min:2|max:50|string',
		'Last name' => 'required|min:2|max:50|string',
		'Contact number' => 'size:11|string',
		'Email' => 'email',
		'Suffix' => 'max:3',
	);

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'First name' => 'min:2|max:100|string',
		'Middle name' => 'min:2|max:50|string',
		'Last name' => 'min:2|max:50|string',
		'Contact number' => 'size:11|string',
		'Email' => 'email',
		'Suffix' => 'max:3',
	);

	public function roomschedule()
	{
		return $this->hasOne('App\RoomSchedule','faculty');
	}

	public function reservation()
	{
		return $this->hasOne('App\Reservation','faculty');
	}

	protected $appends = [
		'full_name',
	];

	public function getFullNameAttribute()
	{
		return trim("$this->lastname, $this->firstname $this->middlename");
	}

}
