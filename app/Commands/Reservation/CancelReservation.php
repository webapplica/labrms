<?php

namespace App\Commands\Reservation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Reservation\Reservation;

class CancelReservation
{
    protected $request;
    protected $id;

    public function __construct(Request $request, $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function handle()
    {
        $request = $this->request;

        // start transaction
        DB::beginTransaction();
        
        $reservation = Reservation::findOrFail($this->id);
        $reservation->cancelNow($request->remarks);
        
        // commit all changes and creates 
        // record in the database fin ende
        DB::commit();
    }
}