<?php

namespace App\Http\Controllers\Inventory\Workstation\Software;

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
		$workstations = App\Workstation::all();
		
		if($request->ajax()) {
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
		$workstation = Workstation::find($id);

		if(count($workstation) <= 0) return view('errors.404');

		return view('workstation.software.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, $id)
	{

		$id = $this->sanitizeString($id);
		$software = $this->sanitizeString($request->get('software'));
		$license = $this->sanitizeString($request->get('softwarelicense'));

		$validator = Validator::make([
			'Workstation' => $id,
			'Software' => $software,
			'License Key' => $license
		], App\Software::$installationRules);


		if($validator->fails())
		{
			if($request->ajax())
			{
				return response()->json([
					'error-messages' => $validator->messages()->toJson()
					
				], 401);
			}
			else
			{

				return redirect()->back()
					->withInput()
					->withErrors($validator);
			}
		}
		DB::beginTransaction();

		App\Software::find($software)->install($id, $license);

		DB::commit();

		if($request->ajax())
		{
			return response()->json([
				'message' => 'success',
			], 200);
		} 


		Session::flash('success-message','Software added to workstation');
		return redirect('workstation/view/software');
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
		Workstationsoftware::find($id);
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
		$id = $this->sanitizeString($id);
		$software = $this->sanitizeString($request->get('software'));
		$license = $this->sanitizeString($request->get('softwarelicense'));

		$validator = Validator::make([
			'Workstation' => $id,
			'Software' => $software,
			'License Key' => $license
		], App\Software::$installationRules);

		if($validator->fails())
		{

			if($request->ajax())
			{
				return response()->json([
					'error-messages' => $validator->messages()->toJson(),
					'message' => '' 
				], 401);
			}
			else
			{
				return redirect()->back()
					->withInput()
					->withErrors($validator);
			}

		}

		DB::beginTransaction();

		App\Software::find($software)->updateSoftwareLicense($id,$license);

		DB::commit();

		return response()->json([
			'error-messages' => [],
			'message' => 'success' 
		], 200);

		Session::flash('success-message','Software updated');
		return redirect('workstation/software');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$id = $this->sanitizeString($id);
		$software = $this->sanitizeString($request->get('software'));

		$validator = Validator::make([
			'Workstation' => $id,
			'Software' => $software
		], App\Software::$installationRules);

		if($request->ajax())
		{

			if($validator->fails())
			{
				return response()->json([
					'error-messages' => $validator->messages()->toJson()
					
				],401);
			}
		}

		if($validator->fails())
		{
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		App\Software::find($software)->uninstall($id);

		DB::commit();


		if($request->ajax())
		{
			return response()->json([
				'message' => 'success',
			], 200);
		} 

		Session::flash('success-message','Software successfully removed from workstation');
		return redirect('workstation/view/software');
	}


}
