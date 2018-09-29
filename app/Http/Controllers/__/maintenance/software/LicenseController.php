<?php

namespace App\Http\Controllers\Maintenance\Software;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LicenseController extends Controller 
{

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function index(Request $request, $id)
	{
		$software = Software::findOrFail($id);
		if($request->ajax()) {
			return datatables($software->licenses())->toJson();
		}

		return view('software.license.index', compact('software'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new AddLicense($request));
		return back()->with('success-message', __('tasks.success'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		License::findOrFail($id)->delete();
		return redirect('maintenance/activity')->with('success-message', __('tasks.success'));
	}
}
