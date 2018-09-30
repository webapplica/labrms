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
			return datatables(Ticket::relatedTo($id)->get())->toJson();
		}

		$ticket = Ticket::findOrFail($id);
		return view('ticket.show', compact('ticket'));

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$ticket = Ticket::findOrFail($id);
		return view('ticket.edit', compact('ticket'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->dispatch(new UpdateTicket($request, $id));
		return redirect('ticket')->with('success-message', __('tasks.success'));

	}

	/**
	 * Transfer ticket to another user.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function transfer(Request $request, $id)
	{
		$this->dispatch(new TransferTicket($request, $id));
		return redirect('ticket')->with('success-message', __('tasks.success'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{

		$this->dispatch(new CloseTicket($request, $id));
		return redirect('ticket')->with('success-message', __('tasks.success'));
	}

	/**
	 * Tags the ticket status as reopened
	 *
	 * @param Request $request
	 * @param [type] $id
	 * @return void
	 */
	public function reopen(Request $request, $id)
	{
		$this->dispatch(new ReopenTicket($request, $id));
		return redirect('ticket')->with('success-message', __('tasks.success'));
	}
	
	/**
	 * Tags the ticket as resolved
	 *
	 * @param Request $request
	 * @return void
	 */
	public function resolve(Request $request)
	{
		$this->dispatch(new ResolveTicket($request, $id));
		return redirect('ticket')->with('success-message', __('tasks.success'));
	}

}
