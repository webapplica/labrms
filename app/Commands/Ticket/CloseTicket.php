<?php

namespace App\Commands\Ticket;

use App\Models\Ticket\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
        $request = $this->request;
        $ticket = Ticket::findOrFail($this->id);

        if(! $ticket->isResolvedStatus()) {
            return;
        }

        DB::beginTransaction();

        $ticket->update([
            'status' => $ticket->getClosedStatus(),
            'closed_by' => Auth::user()->firstname_first
        ]);

		Ticket::create([
            'title' => $request->subject,
            'details' => $request->details,
            'author' => Auth::user()->firstname_first,
            'type_id' => Type::firstOrCreate([ 'name' => 'Action'])->id,
            'user_id' => Auth::user()->id,
            'staff_id' => Auth::user()->id,
            'status' => $ticket->getClosedStatus(),
            'main_id' => $this->id,
            'parent_id' => $this->id,
        ]);

        DB::commit();
	}
}
		