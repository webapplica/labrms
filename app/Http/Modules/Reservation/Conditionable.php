<?php

namespace App\Http\Modules\Reservation;

trait Conditionable
{

	/**
	 * Checks if the current reservation is approved
	 *
	 * @return boolean
	 */
	public function isApproved()
	{
		return $this->is_approved != null;
	}

	/**
	 * Checks if the current reservation is disapproved
	 *
	 * @return boolean
	 */
	public function isDisapproved()
	{
		return $this->is_disapproved != null;
    }
    
	/**
	 * Checks if the current reservation is claimed
	 *
	 * @return boolean
	 */
    public function isClaimed()
    {
        return $this->is_claimed != null;
    }
    
	/**
	 * Checks if the current reservation is cancelled
	 *
	 * @return boolean
	 */
    public function isCancelled()
    {
        return $this->is_cancelled != null;
    }

	/**
	 * Checks if the current reservation is pending reservation
	 *
	 * @return boolean
	 */
    public function isPending()
    {
        return ! ( $this->isCancelled() || $this->isApproved() || $this->isDisapproved() || $this->isClaimed() );
	}
	
	/**
	 * Get the label of the current status of reservation
	 *
	 * @return string
	 */
	public function conditionAsLabel()
	{
		
		// checks if the current reservation is
		// disapproved and return the status for
		// disapproved reservation
		if($this->isDisapproved()) {
			return self::DISAPPROVED_STATUS;
		} 
		
		// checks if the current reservation is
		// cancelled and return the status for
		// cancelled reservation
		else if($this->isCancelled()) {
			return self::CANCELLED_STATUS;
		} 

		// checks if the current reservation is
		// claimed and return the status for
		// claimed reservation
		else if($this->isClaimed()) {
			return self::CLAIMED_STATUS;
		} 
		
		// checks if the current reservation is
		// approved and return the status for
		// approved reservation
		else if($this->isApproved()) {
			return self::APPROVED_STATUS;
		} 
		
		
		return self::PENDING_STATUS;
	}	
	
	public function conditionAsMessage()
	{
		
		// checks if the current reservation is
		// disapproved and returns equivalent message
		if($this->isDisapproved()) {
			return   __('reservation.disapproved_notice');
		} 
		
		// checks if the current reservation is
		// cancelled and returns equivalent message
		else if($this->isCancelled()) {
			return   __('reservation.cancelled_notice');
		} 

		// checks if the current reservation is
		// claimed and returns equivalent message
		else if($this->isClaimed()) {
			return   __('reservation.claimed_notice');
		} 
		
		// checks if the current reservation is
		// approved and returns equivalent message
		else if($this->isApproved()) {
			return   __('reservation.approved_notice', [
				'date' => $this->parsed_date,
				'start' => $this->parsed_start_time,
				'end' => $this->parsed_end_time,
			]);
		} 
		
		
		return __('reservation.pending_notice');
	}
}