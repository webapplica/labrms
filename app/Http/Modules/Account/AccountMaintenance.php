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

	/**
	 * Change the status of user using the type attribute
	 * types can be as follows
	 * activate - the account will be activated
	 * deactivate - the account will be deactivated
	 * 
	 * @param  string $type type of action the user will perform
	 * @return
	 */
	public function toggleStatusByAction(string $action)
	{

		switch($action) {
			case 'activate': 
				$this->status = 1;
				break;
			case 'deactivate':
				$this->status = 0;
				break;
		}

		return $this;
	}
}