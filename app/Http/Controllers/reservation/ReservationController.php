<?php

namespace App\Http\Controllers\Reservation;

use App\Item\Item;
use App\Models\Room;
use App\Models\Purpose;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Reservation\Reservation;
use App\Http\Controllers\Controller;

class ReservationController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{

			$reservation = App\Reservation::orderBy('created_at','desc')
								->get();
			return datatables($reservation)->toJson();
		}

		return view('reservation.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$date = Reservation::thirdWorkingDay(Carbon::now());
		$items = Item::enabledOnReservation()->pluck('property_number', 'id');
		$rooms = Room::pluck('name', 'id');
		$purposes = Purpose::pluck('title', 'id');
		$faculties =  Faculty::all();

		return view('reservation.create', compact('date', 'items', 'rooms', 'purposes', 'faculties'));
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{		
		$this->dispatch(new CreateReservation($request));
		return redirect('reservation/create')->with('success-message');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$reservation = Reservation::findOrFail($id);
		return view('reservation.show', compact('reservation'));
	}


	/**
	*
	*	@param id
	*	@return 'success','error'
	*
	*/
	public function approve(Request $request, $id)
	{
		$this->dispatch(new ApproveReservation($request, $id));
		return redirect('reservation')->with('success-message', __('tasks.success'));
	}

	/**
	*
	*	@param id
	*	@param reason
	*	@return 'success','error'
	*
	*/
	public function disapprove(Request $request, $id)
	{
		$this->dispatch(new DisapproveReservation($request, $id));
		return redirect('reservation')->with('success-message', __('tasks.success'));
	}

	/**
	 * Undocumented function
	 *
	 * @param Request $request
	 * @return void
	 */
	public function claim(Request $request, $id)
	{
		$this->dispatch(new ClaimReservation($request, $id));
		return redirect("lend/create?reservation=$id");
	}
}
