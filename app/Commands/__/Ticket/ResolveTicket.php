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
        $previousTicket = Ticket::findOrFail($this->id);

		// if($previousTicket->status == 'Closed') $previousTicket->close();
		// if($previousTicket->underrepair == 'undermaintenance' || $previousTicket->underrepair == 'working')
		// {
		// 	$previousTicket->setTaggedStatus($previousTicket->id,$underrepair);
        // }
        
        $ticket = $previousTicket->replicate();
        $ticket->setTypeTo('Action');
		$ticket->setStatusToClosed();
		$ticket->generate();
	}
}