<?php

namespace App\Http\Controllers\Maintenance\Software;

use Illuminate\Http\Request;
use App\Models\Software\Software;
use App\Http\Controllers\Controller;
use App\Commands\Software\UpdateSoftware;
use App\Commands\Software\RemoveSoftware;
use App\Commands\Software\RegisterSoftware;

class SoftwareController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			return datatables(Software::all())->toJson();
		}

		return view('software.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		return view('software.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new RegisterSoftware($request));
		return redirect('software')->with('success-message', __('tasks.success'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$software = Software::findOrFail($id);
		return view('software.edit', compact('software'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$this->dispatch(new UpdateSoftware($request, $id));
		return redirect('software')->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new RemoveSoftware($request, $id));
		return redirect('software')->with('success-message', __('tasks.success'));
	}
}
