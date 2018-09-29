<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;

class LogController extends Controller 
{

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
        $inventory = Inventory::with('logs')->findOrFail($id);
        
		if($request->ajax()) {
			return datatables($inventory->logs)->toJson();
		}

		return view('inventory.show', compact('inventory'));
	}

}
