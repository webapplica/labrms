
		$systemunit = $this->sanitizeString($request->get('systemunit'));
		$monitor = $this->sanitizeString($request->get('monitor'));
		$avr = $this->sanitizeString($request->get('avr'));
		$keyboard = $this->sanitizeString($request->get('keyboard'));
		$oskey = $this->sanitizeString($request->get('os'));
		$mouse = $this->sanitizeString($request->get('mouse'));
		$name = $this->sanitizeString($request->get('name'));

		$validator = Validator::make([
			'License Key' => $oskey,
			'AVR' => $avr,
			'Keyboard' => $keyboard,
			'Monitor' => $monitor,
			'System Unit' => $systemunit,
			'Mouse' => $mouse
		],App\Workstation::$rules);

		if($validator->fails())
		{
			return redirect('workstation/create')
					->withInput()
					->withErrors($validator);
		}

		/*
		*
		*	Transaction used to prevent error on saving
		*
		*/
		DB::beginTransaction();

		$systemunit = App\Item::findByPropertyNumber($systemunit)->pluck('id')->first();

		if($monitor != "")
			$monitor = App\Item::findByPropertyNumber($monitor)->first();
		else
			$monitor = null;

		if($keyboard != "")
			$keyboard = App\Item::findByPropertyNumber($keyboard)->first();
		else
			$keyboard = null;

		if($avr != "")
			$avr = App\Item::findByPropertyNumber($avr)->first();
		else
			$avr = null;

		if($mouse != "")
			$mouse = App\Item::findByLocalId($mouse)->first();
		else
			$mouse = null;

		$workstation = new App\Workstation;
		$workstation->systemunit_id = $systemunit;
		$workstation->monitor_id = $monitor;
		$workstation->avr_id = $avr;
		$workstation->keyboard_id = $keyboard;
		$workstation->oskey = $oskey;
		$workstation->mouse_id = $mouse;
		$workstation->assemble();

		DB::commit();