<?php

namespace App\Http\Controllers\ticketing;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Commands\Ticket\TransferTicket;
use App\Http\Requests\TicketRequest\TicketTransferStoreRequest;

class TransferController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $users = User::staffExcept([ Auth::user()->id ])->get();
        return view('ticket.transfer.create', compact('ticket', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TicketTransferStoreRequest $request, $id)
    {
        $this->dispatch(new TransferTicket($request, $id));
        return redirect('ticket/' . $id)->with('success-message', __('tasks.success'));
    }
}
