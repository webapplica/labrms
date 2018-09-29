
		if($request->ajax())
		{
			$workstation = $this->sanitizeString($request->get('selected'));
			$keyboard = $this->sanitizeString($request->get('keyboard'));
			$avr = $this->sanitizeString($request->get('avr'));
			$monitor = $this->sanitizeString($request->get('monitor'));
			$systemunit = $this->sanitizeString($request->get('systemunit'));
			try
			{
				App\Workstation::condemn($workstation,$systemunit,$monitor,$keyboard,$avr);
			} 
			catch ( Exception $e ) 
			{  
				return json_encode('error');
			}

			return json_encode('success');
		}

		$workstation = $this->sanitizeString($request->get('selected'));
		App\Workstation::condemn($workstation,$systemunit,$monitor,$keyboard,$avr);