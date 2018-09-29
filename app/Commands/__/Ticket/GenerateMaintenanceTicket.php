<?php

namespace App\Commands\Ticket;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;

class GenerateMaintenanceTicket
{
	protected $request;
	protected $id;

	public function __construct(Request $request, $id)
	{
		$this->request = $request;
		$this->id = $id;
	}

	public function handle()
	{
		Ticket::find($this->id)
			->assignToCurrentUser()
			->setStatusToOpen()
			->parentIsNull()
			->setTypeTo('Maintenance')
			->generate();
	}
}