
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