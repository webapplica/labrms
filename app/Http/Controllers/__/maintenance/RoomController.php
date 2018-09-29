<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Room\Category as Category;
use App\Http\Requests\RoomRequest\RoomStoreRequest;
use App\Http\Requests\RoomRequest\RoomUpdateRequest;

class RoomController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		if($request->ajax()) {
			return datatables(Room::all())->toJson();
		}

		$categories = Category::pluck('name', 'id');	
		return view('room.index', compact('categories'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = Category::pluck('name', 'id');	
		return view('room.create', compact('categories'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(RoomStoreRequest $request)
	{
		$room = Room::create($request);
		$room->categories()->sync($categories);
		return redirect('room')->with('success-message', __('tasks.success'));
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$room = Room::findOrFail($id);
		return view('room.show', compact('room'));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$room = Room::with('categories')->findOrFail($id);
		return view('room.edit', compact('room'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(RoomUpdateRequest $request, $id)
	{
		$room = Room::findOrFail($id)->update($request);
		$room->categories()->sync($categories);
		return redirect('room')->with('success-message', __('tasks.success'));
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		Room::findOrFail($id)->delete();
		return redirect('room')->with('success-message', __('tasks.success'));
	}
}
