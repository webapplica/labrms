<?php

namespace App\Models;

use Auth;
use Hash;
use Session;
use App\Models\Item;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use App\Http\Modules\Account\AccountRoles;
use App\Http\Modules\Account\PasswordManager;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Modules\Account\SessionsManager;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Modules\Account\NavigationManager;
use App\Http\Modules\Account\AccountMaintenance;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends \Eloquent implements Authenticatable 
{
	use SoftDeletes, AuthenticableTrait, PasswordManager, AccountMaintenance, AccountRoles, NavigationManager, SessionsManager;

	protected $table  = 'users';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	public $timestamps = true;
	protected $fillable = [
		'lastname', 'firstname', 'middlename', 'username', 'password', 
		'contactnumber', 'email', 'type', 'status', 'accesslevel'
	];
	
	protected $hidden = ['password','remember_token'];
	private $navigationLayout = null;
	private static $adminId = 0;
	private static $staffIds = [ 0, 1, 2 ];
	private static $clientIds = [ 3, 4 ];
	private static $defaultPassword = '123456789';
	private static $roles = [
		0 => 'head',
		1 => 'assistant',
		2 => 'staff',
		3 => 'faculty',
		4 => 'student'
	];

	private static $types = [
		'faculty', 'student'
	];

	private static $statusList = [
		0 => 'Inactive',
		1 => 'Active'
	];

	protected $appends = [
		'full_name', 'image_url', 'access_type', 'status_name'
	];

	private static $avatarUrl = [
		0 => 'images/logo/LabHead/labhead-icon-16.png',
		1 => 'images/logo/LabAssistant/assistant-logo-16.png',
		2 => 'images/logo/LabStaff/staff-logo-16.png',
		3 => 'images/logo/Student/student-logo-16.png',
		4 => 'images/logo/Student/student-logo-16.png',
	];

	protected static function getAvatarUrl($id)
	{
		return isset(self::$avatarUrl[$id]) ? self::$avatarUrl[$id] : 'None';
	}

	public function getFullNameAttribute()
	{
		return  $this->lastname . ', ' . $this->firstname . ' ' . trim($this->middlename);
	}

	public function getAccessTypeAttribute()
	{
		return camel_case($this->getCurrentUsersEquivalentRole());
	}

	public function getStatusNameAttribute()
	{
		return camel_case(User::$statusList[ $this->status ]);
	}

	public function getImageUrlAttribute()
	{
		return User::getAvatarUrl($this->accesslevel);
	}

	public function reservation()
	{
		return $this->hasOne(Reservation::class, 'user_id');
	}

	public function itemprofile()
	{
		return $this->belongsToMany(Item::class, Reservation::class, 'user_id', 'item_id');
	}
}
