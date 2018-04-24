<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon;
use Validator;
use DB;
use Session;
use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ItemsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		$type = $request->get('type');
		$status = $request->get('status');

		if($request->ajax())
		{
			$item = new App\Item;

			if($request->has('type'))
			{

				if( ! ($type == 'All' || $type == '') )
				{

					$item = $item->whereHas('inventory', function($query) use ($type) {
						$query->whereHas('itemtype', function($query) use ($type){
							$query->findByType($type);
						});
					})->withTrashed();
				}
			}

			if( !$request->has('status') ) $status = 'working';

			$item = $item->findByStatus($status)
					->with('inventory.itemtype', 'receipt')
					->get();

			return datatables( $item )->toJson();
		}

		
		$item_types = App\ItemType::whereIn('category', ['equipment','fixtures','furniture'])->get();
		$item_statuses = App\Item::distinct('status')->pluck('status');
		return view('item.profile')
				->with('item_statuses',$item_statuses)
				->with('item_types',$item_types)
				->with('current_status', $status)
				->with('current_type', $type);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$id = $this->sanitizeString($request->get('id'));

		$inventory = App\Inventory::find($id);
		$lastprofiled = App\Item::whereHas('inventory', function($query) use($id) {
			$query->where('id', '=', $id);	
		})->orderBy('created_at','desc')
		->pluck('property_number')
		->first();

		$receipts = App\Receipt::whereHas('inventory', function($query) use($id) {
			$query->where('id', '=', $id);	
		})->pluck('number', 'id');

		$locations = App\Room::pluck('name', 'id');

		return view('inventory.item.profile.create')
			->with('inventory', $inventory)
			->with('lastprofiled', $lastprofiled)
			->with('receipts', $receipts)
			->with('locations', $locations)
			->with('id', $id);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{

		$inventory_id = $this->sanitizeString($request->get('inventory_id'));
		$receipt_id = $this->sanitizeString($request->get('receipt_id'));
		$location = $this->sanitizeString($request->get('location'));
		$date_received = $this->sanitizeString($request->get('datereceived'));
		$property_number = "";
		$serial_number = "";
		$quantity = $request->get('quantity');

		DB::beginTransaction();

		foreach($request->get('item') as $item)
		{

			$property_number = $this->sanitizeString($item['propertynumber']);
			$serial_number = $this->sanitizeString($item['serialid']);

			$validator = Validator::make([
				'Property Number' => $property_number,
				'Serial Number' => $serial_number,
				'Location' => $location,
				'Date Received' => $date_received,
				'Status' => 'working'
			], App\Item::$rules,[ 'Property Number.unique' => "The :attribute $property_number already exists" ]);

			if($validator->fails())
			{
				DB::rollback();
				return redirect("item/profile/create?id=$inventory_id")
					->withInput()
					->withErrors($validator);
			}

			$itemprofile = new App\Item;
			$itemprofile->property_number = $property_number;
			$itemprofile->serial_number = $serial_number;
			$itemprofile->location = $location;
			$itemprofile->date_received = Carbon\Carbon::parse($date_received)->toDateString();
			$itemprofile->inventory_id = $inventory_id;
			$itemprofile->receipt_id = $receipt_id;
			$itemprofile->profile();
		}

		$inventory = App\Inventory::find($inventory_id);
		$inventory->log( ($quantity * - 1), 'Item Profiling' );

		DB::commit();

		Session::flash('success-message','Item profiled');
		return redirect('inventory');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		if($request->ajax())
		{
			$items = App\Item::with('inventory')->where('inventory_id','=',$id)->get();
		 	return datatables($items)->toJson();
		}

		$inventory = App\Inventory::find($id);
		if($inventory == null || $inventory->count() == 0)
		{

			return view('errors.404');
		}

		return view('inventory.item.profile.index')
								->with('inventory',$inventory);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$item = App\Item::find($id);
		return view('inventory.item.profile.edit')
			->with('itemprofile',$item);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		$receipt_id = $this->sanitizeString($request->get('receipt_id'));
		$property_number = $this->sanitizeString($request->get('propertyid'));
		$serial_number = $this->sanitizeString($request->get('serialid'));
		$location = $this->sanitizeString($request->get('location'));
		$datereceived = $this->sanitizeString($request->get('datereceived'));

		//validator
		$validator = Validator::make([
				'Property Number' => $property_number,
				'Serial Number' => $serial_number,
				'Location' => $location,
				'Date Received' => $datereceived,
				'Status' => 'working',
				'Location' => 'Server'
			],App\Item::$updateRules);

		if($validator->fails())
		{
			return redirect()->back()
				->withInput()
				->withErrors($validator);
		}

		$itemprofile = App\Item::find($id);
		$itemprofile->propertynumber = $property_number;
		$itemprofile->serialnumber = $serial_number;
		$itemprofile->receipt_id = $receipt_id;
		$itemprofile->location = $location;
		$itemprofile->datereceived = Carbon\Carbon::parse($datereceived);
		$itemprofile->save();

		Session::flash('success-message','Item updated');

		return redirect('inventory');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		
		if($request->ajax()){

			$id = $this->sanitizeString($id);
			
			/**
			*
			*	@param id
			*	@return collection
			*
			*/
			$itemprofile = App\Item::find($id);

			/*
			|--------------------------------------------------------------------------
			|
			| 	Checks if itemprofile is linked to a pc
			|	return 'connected' if linked
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(count(App\Workstation::isWorkstation($itemprofile->propertynumber)) > 0)
			{
				return json_encode('connected');

			}

			/**
			*
			*	Call function condemn
			*
			*/
			App\Inventory::condemn($id);
			return json_encode('success');
		}

		App\Inventory::condemn($item->inventory_id);
		Session::flash('success-message','Item removed from inventory');
		return redirect('inventory');
	}

	/**
	*
	*	Display the ticket
	*	@param $id accepts id of item
	*	@return view
	*
	*/
	public function history(Request $request, $id)
	{
		/**
		*
		*	@param id
		*	@return ticket information
		*	@return inventory
		*	@return itemtype
		*
		*/
		$itemprofile = App\Item::with('tickets')->with('inventory.itemtype')->orderBy('id', 'desc')->find($id);
		
		return view('item.history')
				->with('itemprofile',$itemprofile);
	}

	/**
	*
	*	uses get method
	*	@param $item accepts item id
	*	@param $room accepts room name
	*	@return error or page
	*
	*/
	public function assign(Request $request)
	{
		$item = $this->sanitizeString($request->get('item'));
		$room = App\Room::findByLocation($this->sanitizeString($request->get('room')))->select('id','name')->first();

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if connected to a pc
		|
		|--------------------------------------------------------------------------
		|
		*/
		$item = App\Item::find($item);
		$workstation = App\Workstation::isWorkstation($item->property_number);
		if( $workstation && $workstation->count() > 0)
		{
			Session::flash('error-message','This item is used in a workstation. You cannot remove it here. You need to proceed to workstation');
			return redirect("item/profile/assign");

		}

		App\Item::assignToRoom($item->id, $room);

		Session::flash('success-message',"Item assigned to room $room->name");
		return redirect()->back();
	}

	/**
	*
	*	get receipt based on inventory
	*	uses ajax request
	*	@param inventory id
	*	@return receipt
	*
	*/
	public function getAllReceipt(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$id = $this->sanitizeString($request->get('id'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	if id is not valid
			|
			|--------------------------------------------------------------------------
			|
			*/
			if($id == -1)
			{
				return json_encode('error');
			}
			else
			{
				$receipt = App\Receipt::whereHas('inventory', function($query) use($id) {
					$query->where('id', '=', $id);	
				})->pluck('number', 'id');
				return json_encode($receipt);
			}
		}
	}

	/**
	*
	*	get item brand
	*	uses ajax request
	*	@param itemtype
	*	@return item brand
	*
	*/
	public function getItemBrands(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$itemtype = $this->sanitizeString($request->get('itemtype'));
			if(count($itemtype) > 0)
			{
				$brands = App\Inventory::where('itemtype_id',$itemtype)->select('brand')->get();
			}
			else
			{
				$brands = App\Inventory::select('brand')->get();
			}


			/*
			|--------------------------------------------------------------------------
			|
			| 	return all brand
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode($brands);
		}
	}

	/**
	*
	*	get item model
	*	uses ajax request
	*	@param brand
	*	@return item model
	*
	*/
	public function getItemModels(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$brand = $this->sanitizeString($request->get('brand'));
			if(count($brand) > 0)
			{
				$models = App\Inventory::where('brand',$brand)->select('model')->get();
			}
			else
			{
				$models = App\Inventory::select('model')->get();
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	return all models
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode($models);
		}
	}

	/**
	*
	*	get item brand
	*	uses ajax request
	*	@param itemtype
	*	@return item brand
	*
	*/
	public function getPropertyNumberOnServer(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{

			$model = $this->sanitizeString($request->get('model'));
			$brand = $this->sanitizeString($request->get('brand'));
			$itemtype = $this->sanitizeString($request->get('itemtype'));
			if($model == '' || $brand == '')
			{
				return json_encode('');
			}


			/*
			|--------------------------------------------------------------------------
			|
			| 	get inventory information
			|
			|--------------------------------------------------------------------------
			|
			*/
			$inventory = App\Inventory::where('model',$model)
									->where('brand',$brand)
									->where('itemtype_id',$itemtype)
									->select('id')
									->first();

			/*
			|--------------------------------------------------------------------------
			|
			| 	get property number of item
			|
			|--------------------------------------------------------------------------
			|
			*/
			$propertynumber = App\Item::where('inventory_id',$inventory->id)
											->where('location','Server')
											->select('propertynumber')
											->get();


			/*
			|--------------------------------------------------------------------------
			|
			| 	if item does not exists
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(count($brand) == 0  && count($itemtype) == 0)
			{
				return json_encode('');
			}

			return json_encode($propertynumber);

		}
	}

	/**
	*
	*	get unassigned system unti
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedSystemUnit(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			return App\Item::getUnassignedPropertyNumber('System Unit');
		}
	}

	/**
	*
	*	get unassigned monitor
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedMonitor(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			return App\Item::getUnassignedPropertyNumber('Display');
		}
	}

	/**
	*
	*	get unassigned avr
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedAVR(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			return App\Item::getUnassignedPropertyNumber('AVR');
		}
	}

	/**
	*
	*	get unassigned keyboard
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getUnassignedKeyboard(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			return App\Item::getUnassignedPropertyNumber('Keyboard');
		}
	}

	/**
	*
	*	get all propertynumber
	*	uses ajax request
	*	@return lists of property number
	*
	*/
	public function getAllPropertyNumber(Request $request){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			return json_encode(App\Item::pluck('propertynumber'));
		}
	}

	/**
	*
	*	get item information
	*	uses ajax request
	*	@param propertynumber
	*	@return item information
	*
	*/
	public function getStatus(Request $request, $propertynumber){

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			try{
				$item = App\Item::with('inventory.itemtype')
										->propertyNumber($propertynumber)
										->first();

				/*
				|--------------------------------------------------------------------------
				|
				| 	check if item exists
				|
				|--------------------------------------------------------------------------
				|
				*/
				if(count($item) > 0)
				{
					return json_encode($item);
				}
				else
				{
					return json_encode('error');
				}

			}
			catch ( Exception $e )
			{
				return json_encode('error');
			}
		}
	}

	/**
	*
	*	get list of monitor
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getMonitorList(Request $request)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$monitor = $this->sanitizeString($request->get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get lists of unassembled monitor
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				App\Item::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','Monitor');
								});
							})
							->where('property_number','like','%'.$monitor.'%')
							->pluck('property_number')
			);
		}
	}

	/**
	*
	*	get list of keyboard
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getKeyboardList(Request $request)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$keyboard = $this->sanitizeString($request->get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get keyboard not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				App\Item::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','Keyboard');
								});
							})
							->where('property_number','like','%'.$keyboard.'%')
							->pluck('property_number')
			);
		}
	}

	/**
	*
	*	get list of avr
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getAVRList(Request $request)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$avr = $this->sanitizeString($request->get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get avr not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				App\Item::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','AVR');
								});
							})
							->where('property_number','like','%'.$avr.'%')
							->pluck('property_number')
			);
		}
	}

	/**
	*
	*	get list of system unit
	*	uses ajax request
	*	@param propertynumber
	*	@return lists of property number
	*
	*/
	public function getSystemUnitList(Request $request)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$systemunit = $this->sanitizeString($request->get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get system unit not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				App\Item::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','System Unit');
								});
							})
							->where('property_number','like','%'.$systemunit.'%')
							->pluck('property_number')
			);
		}
	}

	/**
	*
	*	get list of mouse
	*	uses ajax request
	*	@param local id
	*	@return lists of local id
	*
	*/
	public function getMouseList(Request $request)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$systemunit = $this->sanitizeString($request->get('term'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	get system unit not in pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode(
				App\Item::unassembled()
							->whereHas('inventory',function($query){
								$query->whereHas('itemtype',function($query){
									$query->where('name','=','Mouse');
								});
							})
							->where('local_id','like','%'.$systemunit.'%')
							->pluck('local_id')
			);
		}
	}

	/**
	*
	*	chec if inventory is existing
	*	uses ajax request
	*	@param item type
	*	@param brand
	*	@param model
	*	@return inventory information
	*
	*/
	public function checkifexisting(Request $request, $itemtype, $brand, $model)
	{
		$itemtype = $this->sanitizeString($itemtype);
		$brand = $this->sanitizeString($brand);
		$model = $this->sanitizeString($model);

		$itemtype = App\ItemType::type($itemtype)->pluck('id')->first();
		/*
		|--------------------------------------------------------------------------
		|
		| 	get inventory information
		|
		|--------------------------------------------------------------------------
		|
		*/
		$inventory = App\Inventory::brand($brand)
								->model($model)
								->type($itemtype)
								->first();

		if(count($inventory) > 0)
		{
			return json_encode($inventory);
		}
		else
		{
			return json_encode('error');
		}
	}

	public function getItemInformation(Request $request, $propertynumber)
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	Checks if request is made through ajax
		|
		|--------------------------------------------------------------------------
		|
		*/
		if($request->ajax())
		{
			$propertynumber = $this->sanitizeString($propertynumber);
			/*
			|--------------------------------------------------------------------------
			|
			| 	check if item is linked to pc
			|
			|--------------------------------------------------------------------------
			|
			*/
			$item = App\Workstation::isWorkstation($propertynumber);

			/*
			|--------------------------------------------------------------------------
			|
			| 	if not linked to pc, get the item profile information
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(is_null($item) || $item == null)
			{
				$item = App\Item::propertyNumber($propertynumber)->first();
			}
			else
			{
				$item = App\Workstation::with('systemunit')
							->with('keyboard')
							->with('avr')
							->with('monitor')
							->find($item->id);
			}

			if(count($item) == 0)
			{
				return json_encode('error');
			}

			return json_encode($item);
		}
	}


}
