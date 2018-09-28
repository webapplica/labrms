<?php

namespace App\Http\Modules\Ticket;

trait Filterable
{
    
    /**
     * Filter the ticket by the type
     *
     * @param object $query
     * @param string $value
     * @return object
     */
	public function scopeTypeName($query, $value)
	{
		return $query->whereHas('type', function($query) use ($value) {
			$query->where('name', '=', $value);
		});
	}

    /**
     * Filter the ticket by the status
     *
     * @param object $query
     * @param string $value
     * @return object
     */
	public function scopeStatus($query, $value)
	{
		return $query->where('status', '=', $value);
	}

    /**
     * Filter the ticket by staff assigned
     *
     * @param object $query
     * @param string $value
     * @return object
     */
	public function scopeStaffAssigned($query, $value)
	{
		return $query->where('staff_id', '=', $value);
	}

    /**
     * Filter the ticket by open tickets
     *
     * @param object $query
     * @return object
     */
	public function scopeOpen($query)
	{
		return $query->where('status', '=', $this->getOpenStatus());
    }
    
    /**
     * Filter the ticket by closed tickets
     *
     * @param object $query
     * @return object
     */
	public function scopeClosed($query)
	{
		return $query->where('status', '=', $this->getClosedStatus());
	}

    /**
     * Filter the ticket by authors who is currently logged user author
     *
     * @param object $query
     * @return object
     */
	public function scopeSelfAuthored($query)
	{
		$user = Auth::user();
		return $query->where('user_id', '=', $user->id);
	}

    /**
     * Filter the ticket by assigned who is currently logged user author
     *
     * @param object $query
     * @return object
     */
	public function scopeSelfAssigned($query)
	{
		$user = Auth::user();
		return $query->where('staff_id', '=', $user->id);
    }
    
    /**
     * List all the tickets belonging to a workstation
     *
     * @param object $query
     * @param int $id
     * @return object
     */
	public function scopeWorkstationTickets($query, $id)
	{
		return $query->whereHas('workstation', function($query) use ($id) {
			$query->where('id', '=', $id);
		});
	}

    /**
     * List all the tickets that belongs to a room
     *
     * @param object $query
     * @param int $id
     * @return object
     */
	public function scopeRoomTickets($query, $id)
	{
		return $query->whereHas('room', function($query) use ($id) {
			$query->where('id', '=', $id);
		});
	}
}