
		/**
		 * initialize the values
		 * fetched from user
		 * @var [type]
		 */
		$brand = $this->sanitizeString($request->get('brand'));
		$itemtype = $this->sanitizeString($request->get('itemtype'));
		$model = $this->sanitizeString($request->get('model'));
		$quantity = $this->sanitizeString($request->get('quantity'));
		$unit = $this->sanitizeString($request->get('unit'));
		$details = $this->sanitizeString($request->get('details'));
		$_receipt = $this->sanitizeString($request->get('receipt'));

		$receipt = new App\Receipt;

		/**
		 * validator for receipt number
		 * @var [type]
		 */
		$validator = Validator::make([
				'Receipt Number' => $_receipt
			], $receipt->inventoryRules());

		if($validator->fails())
		{
			return redirect('inventory/create')
				->withInput()
				->withErrors($validator);
		}

		/**
		 * validate the inventory
		 * @var [type]
		 */
		$validator = Validator::make([
				'Item Type' => $itemtype,
				'Brand' => $brand,
				'Model' => $model,
				'Details' => $details,
				'Unit' => $unit,
				'Quantity' => $quantity,
				'Profiled Items' => 0
			],App\Inventory::$rules);

		if($validator->fails())
		{
			return redirect('inventory/create')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		/**
		 * check if the receipt already exists,
		 * create if not
		 * @var [type]
		 */
		$receipt = App\Receipt::firstOrCreate([
			'number' => $_receipt
		]);

		$itemtype = App\ItemType::find($itemtype);

		/**
		 * check if the inventory exists in database
		 * fetch the first item it found
		 * @var [type]
		 */
		$inventory = App\Inventory::locate($brand, $model, $itemtype)->first();
		$unit = App\Unit::findByAbbreviation($unit)->first();

		/**
		 * if the items exists, use the existing items
		 * @var App
		 */
		if( !isset($inventory) || $inventory->count() <= 0 ) 
			$inventory = new App\Inventory;

		/**
		 * set all the values before sending to database
		 * @var [type]
		 */
		$inventory->code = App\Inventory::generateCode();
		$inventory->brand = $brand;
		$inventory->itemtype_id = $itemtype->id; 
		$inventory->model = $model;
		$inventory->unit_name = $unit->name;
		$inventory->details = $details;
		$inventory->user_id = Auth::user()->id;
		$inventory->save();
		$inventory->log($quantity, $_receipt);

		/**
		 * insert the values in the pivot table
		 * @var [type]
		 */
		$inventory->receipts()->syncWithoutDetaching(array(
			$receipt->id => [
				'received_quantity' => $quantity,
				'profiled_items' => 0
			]
		));

		DB::commit();