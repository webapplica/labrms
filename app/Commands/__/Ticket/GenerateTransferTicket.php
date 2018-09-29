<?php

namespace App\Commands\Ticket;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;

class GenerateTransferTicket
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
        $previousTicket = Ticket::find($this->id);

		$newTicket->replicate($previousTicket);
		$newTicket->setStatusToOpen();
		$newTicket->staff_id = $request->staff_id;
		$newTicket->comments = $request->comments;
		$newTicket->generate();

		$previousTicket->setStatusToTransferred();
		$previousTicket->save();
	}
}