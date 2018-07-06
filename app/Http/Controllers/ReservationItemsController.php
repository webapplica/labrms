<?php

namespace App\Http\Controllers;

use App;
use Session;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationItemsController extends Controller {

	public $rootUrl;
	public $item;

	public function __construct()
	{
		$root_url = 'reservation/items';
		$item = new App\Item;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{
			$items = App\Item::with('inventory.itemtype')->get();
			return datatables($items)->toJson();
		}
		return view('reservation.item.index');
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request)
	{
		$id = filter_var($request->get('id'), FILTER_SANITIZE_NUMBER_INT );
		$checked = filter_var($request->get('checked'), FILTER_VALIDATE_BOOLEAN);
		$responseCode = 200;
		$messages = [];

		$validator = Validator::make([
			'id' => $id,
			'checked' => $checked,
		], App\Item::$updateForReservationRules);

		if($validator->fails()) {
			$messages = $validator->messages();
			$responseCode = 403;
		}

		$this->item = App\Item::find($id);

		if($checked) {
			$this->item->enabledReservation();
		} else {
			$this->item->disabledReservation();
		}
		
		if($request->ajax()) {
			return response($messages, $responseCode);
		}

		Session:flush('success-message', 'Item has been added for reservation');
		return redirect($rootUrl); 
	}
}
