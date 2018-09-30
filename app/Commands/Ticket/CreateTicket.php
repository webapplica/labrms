<?php

namespace App\Commands\Ticket;

use App\Models\Ticket\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CreateTicket
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle(Ticket $ticket)
	{
        $request = $this->request;

        DB::beginTransaction();

		Ticket::create([
            'title' => $request->subject,
            'details' => $request->details,
            'author' => $request->author ?? Auth::user()->firstname_first,
            'type_id' => Type::firstOrCreate(['name' => $request->type ])->id,
            'user_id' => Auth::user()->id,
            'staff_id' => $request->staff,
            'status' => $ticket->getOpenStatus(),
        ]);

        DB::commit();
	}
}
		