<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Http\Controllers\Controller;
use App\Commands\Ticket\ComplaintTicket;

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
			$ticket = Ticket::oldest('date')->involved()->get();
			return datatables($ticket)->toJson();
		}

		return view('ticket.index')
				->with('ticket', $ticket);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$users = User::staffExcept(User::admin()->pluck('id'))->get();
		$types = Type::pluck('name', 'id');

		return view('ticket.create', compact('users', 'types'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new ComplaintTicket($request));
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
		return redirect('/')->with('success-message', __('tasks.success'));

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
		return redirect('/')->with('success-message', __('tasks.success'));
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
		return redirect('/')->with('success-message', __('tasks.success'));
	}

	/**
	 * Restore the specified resource
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function reopen(Request $request, $id)
	{
		$this->dispatch(new ReopenTicket($request, $id));
		return redirect('/')->with('success-message', __('tasks.success'));
	}
	
	/**
	*
	*	@return ajax: 'success' or 'error'
	*	normal: view with prompt
	*
	*
	*/
	public function resolve(Request $request)
	{
		$this->dispatch(new ResolveTicket($request, $id));
		return redirect('/')->with('success-message', __('tasks.success'));
	}

}
