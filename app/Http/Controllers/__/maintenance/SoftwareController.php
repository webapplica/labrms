<?php

namespace App\Http\Controllers\Maintenance;

use DB;
use App;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SoftwareController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		if($request->ajax())
		{
			$software = App\Software::with('rooms')->get();
			return datatables($software)->toJson();
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
		$name = $this->sanitizeString($request->get('name'));
		$company = $this->sanitizeString($request->get('company'));
		$licensetype = $this->sanitizeString($request->get('licensetype'));
		$softwaretype = $this->sanitizeString($request->get('softwaretype'));
		$minrequirement = $this->sanitizeString($request->get('minrequirement'));
		$maxrequirement = $this->sanitizeString($request->get('maxrequirement'));

		$validator = Validator::make([
				'Software Name' => $name,
				'Software Type' => $softwaretype,
				'License Type' => $licensetype,
				'company' => $company,
				'Minimum System Requirement' => $minrequirement,
				'Recommended System Requirement' => $maxrequirement,
		], App\Software::$rules);

		if($validator->fails())
		{
			return redirect('software/create')
				->withInput()
				->withErrors($validator);
		}

		$software = new App\Software;
		$software->name = $name;
		$software->company = $company;
		$software->license_type = $licensetype;
		$software->type = $softwaretype;
		$software->minimum_requirements = $minrequirement;
		$software->recommended_requirements = $maxrequirement;
		$software->save();

		Session::flash('success-message','Software listed');
		return redirect('software');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		return view('software.edit')
			->with('software', App\Software::find($id));
	}

	public function assign(Request $request, $id)
	{

		$room = App\Room::lists('name','id');
		return view('software.assign')
			->with('room',compact('room'))
			->with('software', App\Software::find($id));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$id = $this->sanitizeString($id);
		$name = $this->sanitizeString($request->get('name'));
		$company = $this->sanitizeString($request->get('company'));
		$licensetype = $this->sanitizeString($request->get('licensetype'));
		$softwaretype = $this->sanitizeString($request->get('softwaretype'));
		$licensekey = $this->sanitizeString($request->get('licensekey'));
		$multiple = $this->sanitizeString($request->get('multiple'));
		$minrequirement = $this->sanitizeString($request->get('minrequirement'));
		$maxrequirement = $this->sanitizeString($request->get('maxrequirement'));

		if($multiple == "on")
		{
			$multiple = 1;
		}

		$validator = Validator::make([
				'Software Name' => $name,
				'Software Type' => $softwaretype,
				'License Type' => $licensetype,
				'company' => $company,
				'Minimum System Requirement' => $minrequirement,
				'Recommended System Requirement' => $maxrequirement,
			], App\Software::$rules);

		$validator = Validator::make([
			'Product Key' => 'licensekey'
		], App\SoftwareLicense::$updateRules);

		if($validator->fails())
		{
			return redirect("software/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$software =  App\Software::find($id);
		$software->name = $name;
		$software->company = $company;
		$software->license_type = $licensetype;
		$software->type = $softwaretype;
		$software->minimum_requirements = $minrequirement;
		$software->recommended_requirements = $maxrequirement;
		$software->save();
		
		Session::flash('success-message','Software updated');
		return redirect('software');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		if($request->ajax())
		{
			$software = App\Software::find($id);
			$software->rooms()->detach();
			$software->forcedelete();
			
			return response()->json([
				'success' => true
			], 200);
		}
		Session::flash('success-message','Software deleted');
		return redirect('software');
	}

	public function assignSoftwareToRoom(Request $request)
	{
		if($request->ajax())
		{
			$id = $this->sanitizeString($request->get('id'));
			$room = $request->get('room');

			$software = App\Software::find($id);
			$software->rooms()->syncWithoutDetaching($room);

			return response()->json([
				'success' => true
			], 200);
		}

		return redirect('software');
	}

	public function removeSoftwareFromRoom(Request $request, $software,$room)
	{
		if($request->ajax())
		{

			$validator = Validator::make([
				'room' => $room,
				'software' => $software
			], [ 
				'room' => 'required|exists:rooms,id',
				'software' => 'required|exists:softwares,id'

			]);

			if( count($software) <= 0 || $validator->fails() )
			{
				return response()->json([
					'errors' => $validator
				], 500);
			} 

			$software = App\Software::find($software);
			$software->rooms()->detach($room);

			return response()->json([
				'success' => true
			], 200);

		}

		Session::flash('success-message','Software removed from room');
		return redirect('software');
	}

	public function getAllSoftwareName(Request $request)
	{
		if($request->ajax())
		{
			$software = App\Software::select('id','softwarename as name')->get();
			return json_encode($software);
		}
	}

	public function getAllSoftwareTypes(Request $request)
	{
		if($request->ajax()){
			return json_encode( App\Software::$types);
		}
	}

	public function getAllLicenseTypes(Request $request, App\Software $software)
	{
		if($request->ajax())
		{
			return json_encode($software->licenseTypes());
		}
	}
}
