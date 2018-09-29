
		$avr = $this->sanitizeString($request->get('avr'));
		$monitor = $this->sanitizeString($request->get('monitor'));
		$os = $this->sanitizeString($request->get('os'));
		$keyboard = $this->sanitizeString($request->get('keyboard'));
		$mouse = $this->sanitizeString($request->get('mouse'));
		$systemunit = $this->sanitizeString($request->get('systemunit'));

		$validator = Validator::make([
		  'Operating System Key' => $os,
		  'System Unit' => $systemunit,
		  'AVR' => $avr,
		  'Keyboard' => $keyboard,
		  'Mouse' => $mouse,
		  'Monitor' => $monitor
		],App\Workstation::$updateRules);

		if($validator->fails())
		{
		  return redirect("workstation/$id/edit")
		    ->withInput()
		    ->withErrors($validator);
		}

		/*
		*
		*	Transaction used to prevent error on saving
		*
		*/
		DB::beginTransaction();

		$workstation = App\Workstation::find($id);
		$workstation->oskey = $os;
		$workstation->mouse_id = $mouse;
		$workstation->monitor_id = $monitor;
		$workstation->avr_id = $avr;
		$workstation->keyboard_id = $keyboard;
		$workstation->systemunit_id = $systemunit;

		$details = "Workstation updated with the following propertynumber:" ;
		$details = $details . "$_avr->propertynumber for AVR";
		$details = $details . "$_monitor->propertynumber for Monitor ";
		$details = $details . "$_keyboard->propertynumber for Keyboard";
		$details = $details .  "$mouse as mouse brand";

		$workstation->updateParts();
		
		DB::commit();