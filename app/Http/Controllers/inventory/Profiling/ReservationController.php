<?php

namespace App\Http\Controllers\Inventory\Profiling;

use Carbon\Carbon;
use App\Models\Item\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\Inventory\Profiling\Reservation\ToggleItem;

class ReservationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $item = Item::findOrFail($id);
        $currentDate = Carbon::now()->toDayDateTimeString();

        return view('inventory.profile.reservation.edit', compact('item', 'currentDate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->dispatch(new ToggleItem($request, $id));
        return back()->with('success-message', __('tasks.success'));
    }
}
