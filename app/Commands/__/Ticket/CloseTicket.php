<?php

namespace App\Commands\Ticket;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;

class CloseTicket
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
        $ticket = Ticket::find($this->id);
		$ticket->closed_by = Auth::user()->firstname . " " . Auth::user()->middlename . " " .Auth::user()->lastname;
		$ticket->setStatusToClosed();
		$ticket->save();
	}
}