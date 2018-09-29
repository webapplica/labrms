
		$quantity = $request->get('quantity');
		$purpose = $request->get('purpose');
		$id = $request->get('id');

		$validator = Validator::make([
				'quantity' => $quantity,
				'purpose' => $purpose,
				'inventory' => $id,
			], App\Inventory::$releaseRules);

		if($validator->fails())
		{

			Session::flash('show-modal', true);
			return back()->withErrors($validator)->withInput();
		}

		$inventory = App\Inventory::find($id);

		if( abs($inventory->quantity) < abs($quantity) )
		{

			Session::flash('show-modal', true);
			return back()->withErrors([
								'Insufficient balance to release'
							])->withInput();
		}

		$inventory->log( $quantity * -1, $purpose);
