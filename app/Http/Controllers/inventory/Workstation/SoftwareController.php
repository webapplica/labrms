<?php

namespace App\Http\Controllers\inventory\workstation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SoftwareController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
		if($request->ajax()) {
            $workstations = Workstation::all();
			return datatables($workstations)->toJson();
		}

		return view('workstation.software.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$workstation = Workstation::findOrFail($id);
		return view('workstation.software.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, $id)
	{
		$this->dispatch(new AssignSoftware($request, $id));
		return redirect("workstation/$id/software")->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		return view('workstation.software.show');
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		Workstation::findOrFail($id);
		return view('workstation.software.edit');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->dispatch(new UpdateLicense($request, $id));
		return redirect("workstation/$id/software")->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new UnassignSoftware($request, $id));
		return redirect("workstation/$id/software")->with('success-message', __('tasks.success'));
	}
}
