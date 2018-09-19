<?php

namespace App\Http\Modules\Account;

trait AccountRoles
{	

	/**
	 * Returns list of staff id
	 * @return array id
	 */
	protected static function getStaffIds()
	{
		return self::$staffIds;
	}

	/**
	 * Returns id for admin
	 * @return array id
	 */
	protected static function getAdminId()
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
	 * @return boolean 
	 */
	public function isStaff()
	{
		return ( in_array( $this->accesslevel, User::getStaffIds() ) );
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
