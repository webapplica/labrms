<?php

namespace App\Http\Controllers\reservation;

use Carbon\Carbon;
use App\Models\Faculty;
use App\Models\Item\Item;
use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Models\Reservation\Purpose;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Models\Reservation\Reservation;
use App\Commands\Reservation\CreateReservation;
use App\Http\Requests\ReservationRequest\ReservationStoreRequest;

class ReservationController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, Reservation $reservation)
	{
		if($request->ajax()) {
			$reservation = Reservation::all();
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
		$items = Inventory::with('items', 'type')
					->authorizedOnReservation()
					->get();
		$rooms = Room::pluck('name', 'id');
		$purposes = ['No rows selected'] + Purpose::pluck('title', 'id')->toArray();
		$personnels =  Faculty::all();
		$suggestedDate = Carbon::now()->addDays(3)->toFormattedDateString();
		$defaultStartTime = Carbon::now()->format('h:iA');
		$defaultReturnTime = Carbon::now()->addHours(3)->format('h:iA');

		return view('reservation.create', compact(
			'items', 'rooms', 'purposes', 'personnels', 'suggestedDate', 'defaultStartTime', 'defaultReturnTime'
		));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(ReservationStoreRequest $request)
	{
		$this->dispatch(new CreateReservation($request));
		return redirect('reservation');
	}
}
