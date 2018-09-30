<?php

namespace App\Http\Controllers\ticketing;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Commands\Ticket\ReopenTicket;
use App\Http\Requests\TicketRequest\TicketReopenStoreRequest;

class ReopenController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('ticket.reopen.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketReopenStoreRequest $request, $id)
    {
        $this->dispatch(new ReopenTicket($request, $id));
        return redirect('ticket/' . $id)->with('success-message', __('tasks.success'));
    }
}
