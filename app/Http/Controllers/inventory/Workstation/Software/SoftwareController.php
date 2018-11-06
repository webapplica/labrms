<?php

namespace App\Http\Controllers\inventory\workstation\software;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;

class SoftwareController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function get(Request $request, $id)
	{		
		$workstation = Workstation::findOrFail($id);
		if($request->ajax()) {
			return datatables($workstation->softwares)->toJson();
		}

		return view('workstation.software.index', compact('workstation'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function show($id)
	// {
	// 	return view('workstation.software.show');
	// }

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function edit($id)
	// {
	// 	Workstation::findOrFail($id);
	// 	return view('workstation.software.edit');
	// }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function update(Request $request, $id)
	// {
	// 	$this->dispatch(new UpdateLicense($request, $id));
	// 	return redirect("workstation/$id/software")->with('success-message', __('tasks.success'));
	// }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function destroy(Request $request, $id)
	// {
	// 	$this->dispatch(new UnassignSoftware($request, $id));
	// 	return redirect("workstation/$id/software")->with('success-message', __('tasks.success'));
	// }
}
