<?php

namespace App\Http\Controllers\reservation;

use Carbon\Carbon;
use App\Models\Purpose;
use App\Models\Faculty;
use App\Models\Item\Item;
use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;

class ListController extends Controller
{

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		// $items = Item::allowedOnReservation()->pluck('property_number', 'id');
		$items = Inventory::with('item', 'type')
					->authorizedOnReservation()
					->get();
		$rooms = Room::pluck('name', 'id');
		$purposes = Purpose::pluck('title', 'id');
		$personnels =  Faculty::all();
		$suggestedDate = Carbon::now()->addDays(3)->toFormattedDateString();
		$defaultStartTime = Carbon::now()->format('h:iA');
		$defaultReturnTime = Carbon::now()->addHours(3)->format('h:iA');

		return view('reservation.create', compact('items', 'rooms', 'purposes', 'personnels', 'suggestedDate', 'defaultStartTime', 'defaultReturnTime'));
	}
}
