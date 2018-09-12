<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App;
use Validator;

class MaintenanceTicketsController extends Controller
{

	/**
	 * [maintenanceView description]
	 * @param  Request $request [description]
	 * @return [type]           [description]
	 */
	public function maintenanceView(Request $request)
	{
		$ticket = App\Ticket::orderBy('created_at', 'desc')->first();
		$activity = MaintenanceActivity::pluck('activity','id');

		if (count($ticket) == 0 )
		{
			$ticket = 1;
		}
		else if ( count($ticket) > 0 )
		{
			$ticket = $ticket->id + 1;
		}

		if(count($activity) == 0)
		{
			$activity = [ 'None' => 'No suggestion available' ];
		}

		return view('ticket.maintenance')
				->with('lastticket',$ticket)
				->with('activity',$activity);
	}


	/**
	 * Maintenance function.
	 *
	 * @return Response
	 */
	public function maintenance(Request $request)
	{

		$tag = $this->sanitizeString($request->get('tag'));
		$ticketname = "Maintenance Ticket";
		$underrepair = false;
		$workstation = false;
		$details = "";

		// check if item is not in the field list
		if($request->has('contains'))
		{
			$details = $this->sanitizeString($request->get('description'));
		}
		else
		{

			// get the activity field
			$activity = $this->sanitizeString($request->get('activity'));
			$maintenanceactivity = MaintenanceActivity::find($activity);
			$ticketname = $maintenanceactivity->activity;

			if(isset($maintenanceactivity->details) && $maintenanceactivity->details != "")
			{
				$details =  $maintenanceactivity->details;
			}
			else
			{
				$details = "No specified details";
			}
		}

		if($request->has('underrepair'))
		{
			$underrepair = true;
		}

		$validator = Validator::make([
				'Details' => $details
		],App\Ticket::$maintenanceRules);

		if($validator->fails())
		{
			return redirect('ticket/maintenance')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		$tickettype = 'Maintenance';
		$author = Auth::user()->firstname . ' ' . Auth::user()->middlename . ' ' . Auth::user()->lastname;
		$staffassigned = Auth::user()->id;
		$status = 'Closed';
		$item = $request->get('item');

		if(count($item) > 0)
		{

			foreach($item as $item)
			{

				$ticket = new App\Ticket;
				$ticket->ticketname = $ticketname;
				$ticket->tickettype = $tickettype;
				$ticket->details = $details;
				$ticket->author = $author;
				$this->undermaintenance = $underrepair;
				$ticket->staffassigned = $staffassigned;
				$ticket->status = $status;
				$ticket->generate($tag);
				
			}

		}

		DB::commit();

		Session::flash('success-message','Ticket Generated');
		return redirect('ticket');

	}
}
