<?php

namespace App\Http\Controllers\inventory;

use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;

class LogController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request, $id)
	{
        $inventory = Inventory::with('logs')->findOrFail($id);
		if($request->ajax()) {
			return datatables($inventory->logs)->toJson();
		}

		return view('inventory.show', compact('inventory'));
	}
}
