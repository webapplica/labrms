<?php

namespace App\Http\Controllers\ticketing;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Commands\Ticket\ActionTicket;
use App\Http\Requests\TicketRequest\TicketActionStoreRequest;

class ActionController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('ticket.action.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketActionStoreRequest $request, $id)
    {
        $this->dispatch(new ActionTicket($request, $id));
        return redirect('ticket/' . $id)->with('success-message', __('tasks.success'));
    }
}
