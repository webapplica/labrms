<?php

namespace App\Http\Controllers\ticketing;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Commands\Ticket\CloseTicket;
use App\Http\Requests\TicketRequest\TicketClosureStoreRequest;

class ClosureController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('ticket.close.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketClosureStoreRequest $request, $id)
    {
        $this->dispatch(new CloseTicket($request, $id));
        return redirect('ticket/' . $id)->with('success-message', __('tasks.success'));
    }
}
