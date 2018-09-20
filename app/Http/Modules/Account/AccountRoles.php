<?php

namespace App\Http\Modules\Account;

use Auth;
use App\Models\User;

trait AccountRoles
{	

	/**
	 * uppercase first letter of each roles
	 * 
	 * @return array
	 */
	public function camelCaseTypes()
	{
		return array_map(function ($callback) {
			return ucfirst($callback);
		}, $this->types());
	}

	/**
	 * Returns list of types the user can choose
	 * 
	 * @return array
	 */
	public function types()
	{
		return self::$types;
	}

	/**
	 * uppercase first letter of each roles
	 * 
	 * @return array
	 */
	public function camelCaseRoles()
	{
		return array_map(function ($callback) {
			return ucfirst($callback);
		}, $this->roles());
	}

	/**
	 * Returns list of roles specified on the model
	 * 
	 * @return array
	 */
	public function roles()
	{
		return User::$roles;
	}

	/**
	 * Returns the equivalent name for the current users role
	 * 
	 * @return String role
	 */
	public function getCurrentUsersEquivalentRole()
	{
		return User::$roles[ $this->accesslevel ];
	}

	/**
	 * Returns list of staff id with exemption if provided
	 * 
	 * @param  array except values to be ignored
	 * @return array id
	 */
	public static function getStaffIds(array $except = [])
	{
		$filtered = array_filter(self::$staffIds, function($callback) use($except) {
			return ! in_array($callback, $except) ? true : false;
		});

		return $filtered;
	}

	/**
	 * Returns id for admin
	 * @return array id
	 */
	public static function getAdminId()
	{
		return self::$adminId;
	}
	/**
	 * Checks if the access level belongs to admin
	 * 
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function scopeAdmin($query)
	{
		return $query->where('accesslevel', '=', 0);
	}

	/**
	 * Checks if the access level belongs to all staff except
	 * current user
	 * 
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function scopeAllLaboratoryUsersExceptCurrentUser($query)
	{
		return $query->whereIn('accesslevel', User::getStaffIds())->where('id', '!=', Auth::user()->id);
	}

	/**
	 * Checks if the access level belongs to all staff
	 * 
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function scopeStaffId($query)
	{
		return $query->whereIn('accesslevel', User::getStaffIds());
	}
	/**
	 * Check if the access if for administator and returns true
	 * if the corresponding level belongs to administrator
	 * 
	 * @return boolean 
	 */
	public function isAdmin()
	{
		return $this->accesslevel == 0;
	}

	/**
	 * Check if the access if for assistant and returns true
	 * if the corresponding level belongs to assistant
	 * 
	 * @return boolean 
	 */
	public function isAssistant()
	{
		return $this->accesslevel == 1;
	}

	/**
	 * Check if the access if for staff and returns true
	 * if the corresponding level belongs to staff
	 *
	 * @param  array except values to be ignored
	 * @return boolean 
	 */
	public function isStaff()
	{
		return ( in_array( $this->accesslevel, User::getStaffIds() ) );
	}

	/**
	 * Check if the access if for staff and returns true
	 * if the corresponding level belongs to staff
	 *
	 * @param  array except values to be ignored
	 * @return boolean 
	 */
	public function isStaffExcept(array $except)
	{
		return ( in_array( $this->accesslevel, User::getStaffIds($except) ) );
	}

	/**
	 * Check if the access if for faculty and returns true
	 * if the corresponding level belongs to faculty
	 * 
	 * @return boolean 
	 */
	public function isFaculty()
	{
		return $this->accesslevel == 3;
	}

	/**
	 * Check if the access if for student and returns true
	 * if the corresponding level belongs to student
	 * 
	 * @return boolean 
	 */
	public function isStudent()
	{
		return $this->accesslevel == 4;
	}
}

