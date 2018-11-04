<?php

namespace App\Http\Controllers\Maintenance\Reservation;

use Illuminate\Http\Request;
use App\Models\Reservation\Purpose;
use App\Http\Controllers\Controller;
use App\Commands\Reservation\Purpose\AddPurpose;
use App\Commands\Reservation\Purpose\UpdatePurpose;

class PurposeController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			return datatables(Purpose::all())->toJson();
		}

		return view('maintenance.reservation.purpose.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('maintenance.reservation.purpose.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new AddPurpose($request));
		return redirect('purpose')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return view('maintenance.reservation.purpose.show');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$purpose = Purpose::findOrFail($id);
		return view('maintenance.reservation.purpose.edit', compact('purpose'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->dispatch(new UpdatePurpose($request, $id));
		return redirect('purpose')->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Purpose::findOrFail($id)->delete();
		return redirect('purpose')->with('success-message', __('tasks.success'));
	}
}
