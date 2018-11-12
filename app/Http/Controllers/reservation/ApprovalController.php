<?php

namespace App\Http\Controllers\reservation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Reservation\Reservation;
use App\Commands\Reservation\ApproveReservation;

class ApprovalController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('reservation.approve', compact('reservation'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->dispatch(new ApproveReservation($request, $id));
        return redirect('reservation/' . $id)->with('success-message', __('tasks.success'));
    }
}
