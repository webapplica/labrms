<?php

namespace App\Http\Modules\Ticket;

trait Fetchable
{
    /**
     * Returns the ticket type attribute value
     *
     * @param string $value
     * @return string
     */
    public function getTicketTypeAttribute($value)
	{
		return ucwords($value);
	}

    /**
     * Returns the ticket details attribute value
     *
     * @param string $value
     * @return string
     */
	public function getDetailsAttribute($value)
	{
		return ucwords($value);
	}

    /**
     * Returns the ticket name attribute value
     *
     * @param string $value
     * @return string
     */
	public function getTicketNameAttribute($value)
	{
		return ucwords($value);
	}

    /**
     * Returns the equivalent status for open ticket
     *
     * @return string
     */
	protected function getOpenStatus()
	{
		return self::OPEN_STATUS;
	}

    /**
     * Returns the equivalent status for closed ticket
     *
     * @return string
     */
	protected function getClosedStatus()
	{
		return self::CLOSED_STATUS;
	}

    /**
     * Returns the equivalent status for transferred ticket
     *
     * @return string
     */
	protected function getTransferredStatus()
	{
		return self::TRANSFERRED_STATUS;
	}

	/**
	 * Returns the table linked to the ticket. Returns null if
	 * the tag cannot be found on any of the tables
	 *
	 * @param string $tag
	 * @return object
	 */
	public function getTagDetails($tag)
	{

		// check if the tag belongs to a workstation
		// returns the workstation information
		if($workstation = Workstation::isWorkstation($tag))
		{
			return $workstation;
		} 

		// check if the tag belongs to a item
		// returns the item information
		else if($item = Item::isItem($tag))  
		{
			return $item;
		}

		// check if the tag belongs to a room
		// returns the room information
		else if($room = Room::exists($tag)) 
		{
			return $room;
		}

		return null;
	}
}