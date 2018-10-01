<?php

namespace App\Http\Controllers\Inventory\Item;

use App\Models\Unit;
use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Commands\Inventory\AddInventory;

class ItemController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$inventory = Inventory::with('type')->get();
			return datatables($inventory)->toJson();
		}

		return view('inventory.item.index')
				->with('title','Inventory');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$types = Type::pluck('name','id');
		$units = Unit::pluck('abbreviation', 'id')->toArray();

		return view('inventory.item.create', compact('types', 'units'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$this->dispatch(new AddInventory($request));
		return redirect('inventory')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function show(Request $request, $id)
	// {

	// 	if($request->ajax()) {
	// 		$inventory = Inventory::with('item.type')->findOrFail($id);
	// 		return datatables($inventory)->toJson();
	// 	}

	// 	return view('inventory.item.show');
	// }
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function edit($id)
	// {
	// 	$inventory = Inventory::findOrFail($id);
	// 	return view('inventory.item.edit', compact('inventory'));
	// }

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function update(Request $request, $id)
	// {
	// 	$this->dispatch(new UpdateItem($request, $id));
	// 	return redirect('inventory')->with('success-message', __('tasks.success'));

	// }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	// public function destroy(Request $request)
	// {
	// 	$this->dispatch(new ReleaseItem($request, $id));
	// 	return redirect("inventory/$id/")->with('success-message', __('tasks.success'));
	// }

}
