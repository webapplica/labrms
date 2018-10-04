<?php

namespace App\Http\Controllers\Maintenance\Software;

use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Models\Software\Software;
use App\Http\Controllers\Controller;
use App\Commands\Software\UpdateSoftware;
use App\Commands\Software\RemoveSoftware;
use App\Commands\Software\RegisterSoftware;
use App\Models\Software\Type as SoftwareType;
use App\Http\Requests\SoftwareRequest\SoftwareStoreRequest;

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

		return view('maintenance.software.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request, Software $software)
	{
		$softwareTypes = SoftwareType::pluck('type', 'type');
		$licenseTypes = $software->getLicenseTypes();

		return view('maintenance.software.create', compact('softwareTypes', 'licenseTypes'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(SoftwareStoreRequest $request)
	{
		$this->dispatch(new RegisterSoftware($request));
		return redirect('software')->with('success-message', __('tasks.success'));
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		$software = Software::findOrFail($id);
		$licenses = $software->licenses;
		$room_assignments = $software->rooms;
		$rooms = Room::whereNotIn('id', $room_assignments->pluck('id'))->get();

		return view('maintenance.software.show', compact('software', 'rooms', 'licenses', 'room_assignments'));

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
		$softwareTypes = SoftwareType::pluck('type', 'type');
		$licenseTypes = $software->getLicenseTypes();

		return view('maintenance.software.edit', compact('software', 'softwareTypes', 'licenseTypes'));
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
