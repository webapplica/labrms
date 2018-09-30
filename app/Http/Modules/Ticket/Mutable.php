<?php

namespace App\Http\Modules\Ticket;

trait Mutable
{
    
	/**
	 * Sets the current ticket type to uppercase value
	 *
	 * @param string $value
	 * @return string
	 */
	public function setTickettypeAttribute($value)
	{
		$this->attributes['type'] = ucwords($value);
	}

	/**
	 * Sets the parent id to null
	 * 
	 * @return object
	 */
	public function parentIsNull()
	{
		$this->parent_id = null;

		return $this;
	}
	
	/**
	 * Set the ticket type to the argument listed. If the name exists in database
	 * fetches the record, else it creates a new type
	 *
	 * @param string $type
	 * @return object
	 */
	public function setTypeTo($type)
	{
		$this->type_id = Type::firstOrCreate([
			'name' => 'Maintenance'
		])->id;

		return $this;
	}

	/**
	 * Sets the ticket status to open
	 * 
	 * @return object
	 */
	public function setStatusToOpen()
	{
		$this->status = self::OPEN_STATUS;

		return $this;
	}

	/**
	 * Sets the ticket status to closed
	 * 
	 * @return object
	 */
	public function setStatusToClosed()
	{
		$this->status = self::CLOSED_STATUS;

		return $this;
	}

	/**
	 * Sets the ticket status to transferred
	 * 
	 * @return object
	 */
	public function setStatusToTransferred()
	{
		$this->status = self::TRANSFERRED_STATUS;

		return $this;
	}

	/**
	 * Assigns the ticket to the current user
	 * 
	 * @return object
	 */
	public function assignToCurrentUser()
	{
		$this->staff_id = Auth::user()->id;

		return $this;
	}

	/**
	 * Sets the details of the ticket
	 *
	 * @param string $details
	 * @return object
	 */
	public function withDetails($details)
	{
		$this->details = $details;

		return $this;
	}

	/**
	 * Sets the title of the ticket
	 *
	 * @param string $title
	 * @return object
	 */
	public function withTitle($title)
	{
		$this->title = $title;

		return $this;
	}
}