<?php

namespace App\Commands\Ticket;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;

class ComplaintTicket
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		Ticket::find($this->id)
			->assignToCurrentUser()
			->setStatusToOpen()
			->parentIsNull()
			->setTypeTo('Complaint')
			->generate($request->tag);
	}
}