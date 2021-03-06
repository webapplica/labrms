<?php

namespace App\Http\Controllers\Maintenance\Item;

use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Commands\Item\Type\AddType;
use App\Http\Controllers\Controller;
use App\Commands\Item\Type\UpdateType;
use App\Http\Requests\ItemTypeRequest\TypeStoreRequest;
use App\Http\Requests\ItemTypeRequest\TypeUpdateRequest;

class TypeController extends Controller 
{
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			return datatables(Type::all())->toJson();
		}

		return view('maintenance.item.type.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Type $type)
	{
		return view('maintenance.item.type.create', compact('type'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(TypeStoreRequest $request)
	{
		$this->dispatch(new AddType($request));
		return redirect('item/type')->with('success-message', __('tasks.success'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$type = Type::findOrFail($id);
		return view('maintenance.item.type.edit', compact('type'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(TypeUpdateRequest $request, $id)
	{
		$this->dispatch(new UpdateType($request, $id));
		return redirect('item/type')->with('success-message', __('tasks.success'));
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Type::findOrFail($id)->delete();
		return redirect('item/type')->with('success-message', __('tasks.success'));

	}
}
