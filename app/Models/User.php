<?php

namespace App\Models;

use Auth;
use Hash;
use Session;
use Illuminate\Database\Eloquent\Model;
use App\Http\Managers\User\PasswordManager;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends \Eloquent implements Authenticatable 
{
	use SoftDeletes, AuthenticableTrait, PasswordManager;

	/**
	*
	* table name
	*
	*/	
	protected $table  = 'users';

	/**
	*
	*	fields to be set as date
	*
	*/
	protected $dates = ['deleted_at'];

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
		'username',
		'password',
		'contactnumber',
		'email',
		'type',
		'status',
		'accesslevel'
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
		'Username' => 'required_with:password|min:4|max:20|unique:users,username',
		'Password' => 'required|min:8|max:50',
		'First name' => 'required|between:2,100|string',
		'Middle name' => 'min:2|max:50|string',
		'Last name' => 'required|min:2|max:50|string',
		'Contact number' => 'required|size:11|string',
		'Email' => 'required|email'
	);

	/**
	*
	* update rules
	*
	*/
	public static $updateRules = array(
		'Username' => 'min:4|max:20',
		'Password' => 'min:6|max:50',
		'First name' => 'min:2|max:100|string',
		'Middle name' => 'min:2|max:50|string',
		'Last name' => 'min:2|max:50|string',
		'Contact number' => 'size:11|string',
		'email' => 'email'
	);

	private static $staffIds = [
		0, 1, 2	
	];

	private static $clientIds = [
		3, 4
	];

	public static $roles = [
		0 => 'head',
		1 => 'assistant',
		2 => 'staff',
		3 => 'faculty',
		4 => 'student'
	];

	private static $avatarUrl = [
		0 => 'images/logo/LabHead/labhead-icon-16.png',
		1 => 'images/logo/LabAssistant/assistant-logo-16.png',
		2 => 'images/logo/LabStaff/staff-logo-16.png',
		3 => 'images/logo/Student/student-logo-16.png',
		4 => 'images/logo/Student/student-logo-16.png',
	];

	protected static function getStaffIds()
	{
		return self::$staffIds;
	}

	protected static function getAvatarUrl($id)
	{
		return isset(self::$avatarUrl[$id]) ? self::$avatarUrl[$id] : 'None';
	}

	protected $appends = [
		'full_name', 'image_url'
	];

	public function getFullNameAttribute()
	{
		return  "$this->lastname,$this->firstname $this->middlename";
	}

	public function getImageUrlAttribute()
	{
		return User::getAvatarUrl($this->accesslevel);
	}

	public function reservation()
	{
		return $this->hasOne('App\Reservation','user_id');
	}

	public function itemprofile()
	{
		return $this->belongsToMany('App\ItemProfile','Reservation','user_id','item_id');
	}

	public function scopeAdmin($query)
	{
		return $query->where('accesslevel','=',0);
	}

	public function scopeStaffId($query)
	{
		return $query->whereIn('accesslevel', [0, 1, 2]);
	}

	/**
	 * Clears authentication and session of the user
	 *
	 * @return void
	 */
	public static function clear()
	{

		$user = [];
		if(Auth::check()) {
			$user = Auth::user();
			Auth::logout();
		}

		Session::flush();

		return $user;
	}

	public function isStaff()
	{
		return ( in_array( $this->accesslevel, User::getStaffIds() ) );
	}
}
