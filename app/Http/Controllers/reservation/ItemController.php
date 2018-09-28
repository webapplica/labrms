<?php

namespace App\Http\Controllers\Reservation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$items = Item::with('inventory.type')->get();
			return datatables($items)->toJson();
		}

		return view('reservation.item.index');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->dispatch(new ToggleItem($request, $id));
		return redirect('reservation/item')->with('success-message', __('tasks.success')); 
	}
}
