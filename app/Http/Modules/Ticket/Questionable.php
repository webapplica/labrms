<?php

namespace App\Http\Modules\Ticket;

trait Questionable
{
    
	/**
	 * Check if the current status is open and returns true
	 *
	 * @param string $value
	 * @return string
	 */
	public function isOpenStatus()
	{
		return $this->status == $this->getOpenStatus();
	}
    
	/**
	 * Check if the current status is closed and returns true
	 *
	 * @param string $value
	 * @return string
	 */
	public function isClosedStatus()
	{
		return $this->status == $this->getClosedStatus();
	}
    
	/**
	 * Check if the current status is resolved and returns true
	 *
	 * @param string $value
	 * @return string
	 */
	public function isResolvedStatus()
	{
		return $this->status == $this->getResolvedStatus();
	}
}