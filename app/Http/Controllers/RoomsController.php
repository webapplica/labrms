<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App;
use Illuminate\Http\Request;

class RoomsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		$rooms = App\Room::all();

		if($request->ajax())
		{
			return datatables($rooms)->toJson();
		}

		$categories = App\RoomCategory::pluck('name', 'id');	

		return view('room.index')
			->with('rooms',$rooms)
			->with('categories', $categories);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$categories = App\RoomCategory::pluck('name', 'id');	
		return view('room.create')
				->with('categories', $categories);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		$name = $this->sanitizeString($request->get("name"));
		$description = $this->sanitizeString($request->get('description'));
		$categories = $request->get('category');
		$room = new App\Room;

		foreach($categories as $category)
		{
			$validator = Validator::make([
				'Name' => $name,
				'Description' => $description,
				'Category' => $category
			], $room->rules());

			if($validator->fails())
			{
				return redirect('room/create')
					->withInput()
					->withErrors($validator);
			}

		}

		$room->name = $name;
		$room->description = $description;
		$room->status = 0;
		$room->save();

		$room->categories()->sync($categories);

		Session::flash('success-message','Room information created!');
		return redirect('room');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$id = $this->sanitizeString($id);

		$room = App\Room::find($id);

		return view('room.show')
				->with('room',$room);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$room = Room::find($id);
		$categories = App\RoomCategory::pluck('name', 'id');	
		return view('room.update')
			->with('room',$room)
			->with('categories', $categories);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$name = $this->sanitizeString($request->get("name"));
		$description = $this->sanitizeString($request->get('description'));

		$validator = Validator::make([

			'Name' => $name,
			'Description' => $description

		],Room::$updateRules);

		if($validator->fails())
		{
			return redirect("room/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$room = Room::find($id);
		$room->name = $name;
		$room->description = $description;
		$room->category = $category;
		$room->status = 'working';
		$room->save();

		Session::flash('success-message','Room information updated!');
		return redirect('room');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		if($request->ajax()){
			try{
				$room = Room::find($id);
				$room->delete();
				return json_encode('success');
			} catch( Exception $e ){}
		}

		$room = Room::findOrFail($id);
		$room->delete();
		Session::flash('success-message','Room information deleted');
		return redirect('room');
	}

	/**
	 * returns list of room tickets
	 * the url required for this is 
	 * ticket/room/{id}
	 * @param id as room id
	 * @return list of tickets based on the room
	 */
	public function getRoomTickets(Request $request, $id)
	{
		$validator = Validator::make([
			'Room' => $id
		], [ 'Room' => 'required|exists:rooms,id']);

		if($validator->fails())
		{
			return response()->json([
				'Operation' => false,
				'errors' => $validator
			], 200);
		}

		$rooms = App\Room::with('tickets')->find($id);
		return datatables($rooms->tickets)->toJson();
	}

}
