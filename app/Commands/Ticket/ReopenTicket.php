<?php

namespace App\Commands\Ticket;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;

class ReopenTicket
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
		$ticket = $previousTicket->replicate();
		$ticket->setStatusToOpen();
		$ticket->generate();
	}
}