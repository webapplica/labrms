<?php

namespace App\Http\Controllers\Lent;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return datatables(Item::all())->toJson();
        }

        return view('lend.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentDate = Carbon::now()->toFormattedDateString();
        $reservation = null;

        if($request->reservation) {
            $reservation = Reservation::with('user')->findOrFail($request->reservation);
        }

        return view('lend.create', compact('reservation', 'currentDate'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->dispatch(new LendItem($request));
        return redirect('lend')->with('success-message', __('tasks.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->dispatch(new ReturnLentItem($id));
        return redirect('lend')->with('success-message', __('tasks.success'));
    }
}
