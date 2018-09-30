<?php

namespace App\Http\Controllers\ticketing;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Commands\Ticket\ResolveTicket;
use App\Http\Requests\TicketRequest\TicketResolveStoreRequest;

class ResolutionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        return view('ticket.resolve.create', compact('ticket'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketResolveStoreRequest $request, $id)
    {
        $this->dispatch(new ResolveTicket($request, $id));
        return redirect('ticket/' . $id)->with('success-message', __('tasks.success'));
    }
}
