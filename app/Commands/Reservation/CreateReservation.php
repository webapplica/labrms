<?php

namespace App\Commands\Reservation;

use Carbon\Carbon;
use App\Models\Faculty;
use App\Models\Item\Item;
use App\Models\Room\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation\Purpose;
use Illuminate\Support\Facades\Auth;
use App\Models\Reservation\Reservation;

class CreateReservation
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
        $request = $this->request;
        $faculty = Faculty::findOrfail($request->faculty);
        $location = Room::findOrFail($request->location)->name;
        $start = Carbon::parse($request->date . ' ' . $request->time_start);
        $end = Carbon::parse($request->date . ' ' . $request->return_time);
        $items = [];

        // checks if the not in the list checkbox
        // is ticked and use the data from the
        // textbox if the checkbox is ticked
        if($request->not_in_list) {

            // assigns the request alternative explanation 
            // data to the purpose variable
            $purpose = $request->alternative_explanation;

            // adds the new record to purpose table as a 
            // suggestions for future reservations of users
            Purpose::firstOrCreate(
                ['title' => str_limit($purpose, 30)], ['description' => $purpose]
            )->description;
        }

        // if the checkbox is not ticked, 
        // use the data from the purpose and selects
        // the title attribute as the value of the field
        else {
            $purpose = Purpose::findOrFail($request->purpose)->title;
        }

        // start transaction
        DB::beginTransaction();

        // creates a record in reservation with the
        // information provided in the request
		$reservation = Reservation::create([
            'user_id' => Auth::user()->id,
            'start' => $start,
            'end' => $end,
            'purpose' => $purpose,
            'location' => $location,
            'is_approved' => null,
            'reservee' => Auth::user()->full_name,
            'accountable' => $faculty->full_name,
            'faculty_id' => $faculty->id,
        ]);
        
        // loops through each item selected and choose
        // the first item in the list which is allowed 
        // on reservation and is not borrowed on the same
        // date as other reservation
        // THIS ALGORITHM NEEDS REFACTORING
        foreach($request->items as $inventory) {
            $items[] = Item::inInventory($inventory)
                        ->allowedOnReservation()
                        ->notReservedOn($start, $end)
                        ->first()
                        ->id;
        }

        // attach the item to the current reservation
        // the item is array from the function above
        // this comment
        $reservation->item()->attach($items);
        
        // commit all changes and creates 
        // record in the database fin ende
        DB::commit();
	}
}