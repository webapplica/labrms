<?php

namespace App\Http\Controllers\Maintenance;

use Session;
use Validator;
use App\Software;
use App\SoftwareLicense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class SoftwareLicenseController extends Controller {


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{

		$software_id = $this->sanitizeString(Input::get("id"));
		$licensekey = $this->sanitizeString(Input::get("licensekey"));
		$multiple = $this->sanitizeString(Input::get('usage'));

		$validator = Validator::make([
			'Product Key' => $licensekey
		],SoftwareLicense::$rules);

		if($validator->fails())
		{
			Session::flash('error-message','Problem encountered while processing your request');
			return redirect()->back();
		}

		$softwarelicense = new SoftwareLicense;
		$softwarelicense->software_id = $software_id;
		$softwarelicense->key = $licensekey;
		$softwarelicense->usage = $multiple;
		$softwarelicense->save();

		Session::flash('success-message','Software License Added');
		return redirect("software/license/$software_id");
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
			return json_encode([
				'data' => Softwarelicense::where('software_id','=',$id)->get()
			]);
		}

		$software = Software::where('id','=',$id)
								->first();
		return view('software.show')
			->with('software',$software);
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
			$license = SoftwareLicense::find($id);
			$license->delete();
			return json_encode('success');
		}

		$license = SoftwareLicense::find($id);
		$license->delete();
		
		Session::flash('success-message','Software License removed');
		return redirect('maintenance/activity');
	}

	public function getAllSoftwareLicenseKey($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);
			$software = Software::find($id);

			return json_encode($software->licenses);
		}
	}

	public function getSoftwareLicense($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);
			$licensekey = $this->sanitizeString(Input::get('term'));
			$licenses = SoftwareLicense::where('software_id','=',$id)
								->where('key', 'like', "%$licensekey%")
								->pluck('key');
			return json_encode( $licenses );
		}

	}


}
