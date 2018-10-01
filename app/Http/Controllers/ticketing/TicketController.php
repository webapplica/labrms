<?php

namespace App\Http\Controllers\Ticketing;

use App\Models\User;
use App\Models\Ticket\Type;
use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use App\Http\Controllers\Controller;
use App\Commands\Ticket\CreateTicket;
use App\Http\Requests\TicketRequest\TicketStoreRequest;

class TicketController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, Ticket $ticket)
	{
		if($request->ajax()) {
			$ticket = Ticket::authorIsCurrentUser()->root()->oldest('date')->get();
			return datatables($ticket)->toJson();
		}

		return view('ticket.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$types = Type::fetchOnly(['Complaint', 'Maintenance', 'Incident'])->pluck('id', 'name');
		return view('ticket.create', compact('types'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TicketStoreRequest $request)
	{
		$this->dispatch(new CreateTicket($request));
		return redirect('ticket')->with('success-message', __('tasks.success'));

	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		if($request->ajax()) {
			$id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
			return datatables(Ticket::relatedTo($id)->latest('created_at')->get())->toJson();
		}

		$ticket = Ticket::findOrFail($id);
		return view('ticket.show', compact('ticket'));

	}
}
