
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