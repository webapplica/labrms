<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model 
{

	protected $table  = 'faculties';
	protected $primaryKey = 'id';
	public $timestamps = true;
	protected $fillable = [
		'lastname', 'firstname', 'middlename', 'title',
		'suffix', 'contactnumber', 'email',
	];

	protected $hidden = ['password','remember_token'];

	protected $appends = [
		'full_name',
	];

	public function getFullNameAttribute()
	{
		return trim("$this->lastname, $this->firstname $this->middlename");
	}

}
