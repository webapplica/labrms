<?php

namespace App\Http\Controllers\Inventory\Workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkstationController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$workstations = Workstation::all();
			return datatables($workstations)->toJson();
		}

		return view('workstation.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('workstation.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new AssembleWorkstation($request));	
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		$workstation = Workstation::findOrFail($id);
		if($request->ajax()) {
			return datatables($workstation->tickets)->toJson();
		}

		return view('workstation.show');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$workstation = Workstation::findOrFail($id);
		return view('workstation.edit');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->dispatch(new UpdateWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new DisassembleWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}
