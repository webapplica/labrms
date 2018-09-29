
		//inventory
		$brand = $this->sanitizeString($request->get('brand'));
		$itemtype = $this->sanitizeString($request->get('itemtype'));
		$model = $this->sanitizeString($request->get('model'));
		$unit = $this->sanitizeString($request->get('unit'));
		$warranty = $this->sanitizeString($request->get('warranty'));
		$details = $this->sanitizeString($request->get('details'));

		//validator
		$validator = Validator::make([
				'Item Type' => $itemtype,
				'Brand' => $brand,
				'Model' => $model,
				'Details' => $details,
				'Warranty' => $warranty,
				'Unit' => $unit,
				'Quantity' => 0,
				'Profiled Items' => 0
			],App\Inventory::$rules);

		if($validator->fails())
		{
			return redirect("inventory/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$inventory = App\Inventory::find($id);
		$inventory->brand = $brand;
		$inventory->model = $model;
		$inventory->itemtype_id = $itemtype;
		$inventory->details = $details;
		$inventory->warranty = $warranty;
		$inventory->unit = $unit;
		$inventory->save();