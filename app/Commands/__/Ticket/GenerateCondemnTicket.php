<?php

namespace App\Commands\Ticket;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;

class GenerateCondemnTicket
{
	protected $request;
    protected $id;
    protected $tag;

	public function __construct(Request $request, $id, $tag)
	{
		$this->request = $request;
        $this->id = $id;
        $this->tag = $tag;
	}

	public function handle()
	{
		$currentDate = Carbon::now()->toDayDateTimeString();
     
		Ticket::find($this->id)
            ->assignToCurrentUser()
            ->setStatusToClosed()
            ->parentIsNull()
            ->setTypeTo('Condemn')
            ->withTitle('Item Condemn')
            ->withDetails('Item Condemned on ' . $currentDate)
            ->generate($this->tag);
	}
}