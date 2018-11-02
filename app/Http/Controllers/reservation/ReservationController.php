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

class ReservationController extends Controller
{

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		// $items = Item::allowedOnReservation()->pluck('property_number', 'id');
		$items = Inventory::with('items', 'type')
					->authorizedOnReservation()
					->get();
		$rooms = Room::pluck('name', 'id');
		$purposes = ['No rows selected'] + Purpose::pluck('title', 'id')->toArray();
		$personnels =  Faculty::all();
		$suggestedDate = Carbon::now()->addDays(3)->toFormattedDateString();
		$defaultStartTime = Carbon::now()->format('h:iA');
		$defaultReturnTime = Carbon::now()->addHours(3)->format('h:iA');

		return view('reservation.create', compact('items', 'rooms', 'purposes', 'personnels', 'suggestedDate', 'defaultStartTime', 'defaultReturnTime'));
	}
}
