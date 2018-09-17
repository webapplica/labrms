<?php

namespace App\Models;

use Auth;
use Hash;
use Session;
use App\Models\Item;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Model;
use App\Http\Managers\User\PasswordManager;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Modules\Account\AccountMaintenance;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends \Eloquent implements Authenticatable 
{
	use SoftDeletes, AuthenticableTrait, PasswordManager, AccountMaintenance;

	protected $table  = 'users';
	protected $primaryKey = 'id';
	protected $dates = ['deleted_at'];
	public $timestamps = true;
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

	protected $hidden = ['password','remember_token'];

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

	protected function getCorrespondingView()
	{
		// return [];
	}

	protected static function getStaffIds()
	{
		return self::$staffIds;
	}

	protected static function getAdminId()
	{
		return 0;
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
		return $this->hasOne(Reservation::class, 'user_id');
	}

	public function itemprofile()
	{
		return $this->belongsToMany(Item::class, Reservation::class, 'user_id', 'item_id');
	}

	public function scopeAdmin($query)
	{
		return $query->where('accesslevel', '=', 0);
	}

	public function scopeallLaboratoryUsersExceptCurrentUser($query)
	{
		return $query->whereIn('accesslevel', User::getStaffIds())->where('id', '!=', Auth::user()->id);
	}

	public function scopeStaffId($query)
	{
		return $query->whereIn('accesslevel', User::getStaffIds());
	}

	/**
	 * Clears authentication and session of the user
	 *
	 * @return void
	 */
	public static function clear()
	{
		if(Auth::check()) {
			$user = Auth::user();
			Auth::logout();
		}

		Session::flush();
		return isset($user) ? $user : [];
	}

	public function isStaff()
	{
		return ( in_array( $this->accesslevel, User::getStaffIds() ) );
	}

	public function validateCreate($request)
	{
		$validator = $this->validate([
			'username' => 'required_with:password|min:4|max:20|unique:' . $this->table . ',username',
			'firstname' => 'required|between:2,100|string',
			'middlename' => 'min:2|max:50|string',
			'lastname' => 'required|min:2|max:50|string',
			'contactnumber' => 'required|size:11|string',
			'email' => 'required|email'
		]);
	}

	public function updateAccessLevel($new)
	{
		$this->accesslevel = $new;
	}
}
