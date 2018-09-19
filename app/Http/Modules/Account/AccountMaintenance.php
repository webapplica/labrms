<?php

namespace App\Http\Modules\Account;

trait AccountMaintenance
{

	/**
	 * Sets the current access level to the new access level
	 * provided
	 * 
	 * @param  int $newAccessLevel access level value to be assigned
	 * @return 
	 */
	public function updateAccessLevel(int $newAccessLevel)
	{
		$this->accesslevel = $newAccessLevel;
	}
}