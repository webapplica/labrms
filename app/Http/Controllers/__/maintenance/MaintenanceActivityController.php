<?php

namespace App\Http\Controllers\Maintenance;

use App;
use Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class MaintenanceActivityController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([
				'data' => App\Activity::all()
			]);
		}
		
		return view('maintenance.activity.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('maintenance.activity.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$type = $this->sanitizeString(Input::get('maintenancetype'));
		$name = $this->sanitizeString(Input::get('activity'));
		$details = $this->sanitizeString(Input::get('details'));

		$validator = Validator::make([
			'Type' => $type,
			'Activity' => $name,
			'Details' => $details
		],App\Activity::$rules);

		if($validator->fails())
		{
			return redirect('maintenance/activity/create')
				->withInput()
				->withErrors($validator);
		}

		$maintenanceactivity = new App\Activity;
		$maintenanceactivity->type = $type;
		$maintenanceactivity->name = $name;
		$maintenanceactivity->details = $details;
		$maintenanceactivity->save();

		Session::flash('success-message','Activity added');
		return redirect('maintenance/activity');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
		if(Request::ajax())
		{
			return json_encode( App\Activity::find($id) );
		}
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		try{
			$maintenanceactivity = App\Activity::find($id);
			return view('maintenance.activity.edit')
					->with('maintenanceactivity',$maintenanceactivity);
		} catch( Exception $e ){

		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$type = $this->sanitizeString(Input::get('maintenancetype'));
		$name = $this->sanitizeString(Input::get('activity'));
		$details = $this->sanitizeString(Input::get('details'));

		$validator = Validator::make([
			'Activity' => $name
		],App\Activity::$updateRules);

		if($validator->fails())
		{
			return redirect('maintenance/activity/create')
				->withInput()
				->withErrors($validator);
		}

		$maintenanceactivity = App\Activity::find($id);
		$maintenanceactivity->type = $type;
		$maintenanceactivity->name = $name;
		$maintenanceactivity->details = $details;
		$maintenanceactivity->save();

		Session::flash('success-message','Activity updated');
		return redirect('maintenance/activity/');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString(Input::get('id'));
			$maintenanceactivity = App\Activity::find($id);
			$maintenanceactivity->delete();
			return json_encode('success');
		}

		$maintenanceactivity = App\Activity::find($id);
		$maintenanceactivity->delete();
		Session::flash('success-message','Activity removed');
		return redirect('maintenance/activity');
	}

	/**
	*	input type and returns maintenance activity
	*	based on the said type
	*	@param type
	*	@return list of maintenance activity
	*
	*/ 
	public function getMaintenanceActivity()
	{
		if(Request::ajax())
		{
			$type = $this->sanitizeString(Input::get('type'));

			return json_encode(App\Activity::type($type)->pluck('details','id'));
		}
	}

	public function getAllMaintenanceActivity()
	{

		return json_encode(['data'=>App\Activity::all()]);
	}

	public function getPreventiveMaintenanceActivity()
	{
		return json_encode(App\Activity::where('type','preventive')->select('problem')->get());
	}

	public function getCorrectiveMaintenanceActivity()
	{
		return json_encode(App\Activity::where('type','corrective')->select('problem')->get());
	}


}
