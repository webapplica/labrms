<?php

namespace App\Models;

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
	protected $appends = ['full_name'];

	/**
	 * Return formatted fullname attribute
	 *
	 * @return void
	 */
	public function getFullNameAttribute()
	{
		return $this->lastname . ', ' . $this->firstname . trim($this->middlename);
	}

}
