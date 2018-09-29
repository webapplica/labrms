<?php

namespace App\Http\Controllers\Maintenance\Room;

use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Models\Room\Category;
use App\Commands\Room\AddRoom;
use App\Commands\Room\UpdateRoom;
use App\Http\Controllers\Controller;
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
		return view('maintenance.room.index', compact('categories'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = Category::pluck('name', 'id');	
		return view('maintenance.room.create', compact('categories'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(RoomStoreRequest $request)
	{
		$this->dispatch(new AddRoom($request));
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
		return view('maintenance.room.show', compact('room'));
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
		$categories = Category::pluck('name', 'id');	
		return view('maintenance.room.edit', compact('room', 'categories'));
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(RoomUpdateRequest $request, $id)
	{
		$this->dispatch(new UpdateRoom($request, $id));
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
