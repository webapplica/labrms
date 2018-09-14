<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App;
use Auth;
use DB;
use Session;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ItemInventoryController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
		{
			$inventory = App\Inventory::with('itemtype')->get();
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
		$units = [ null => 'None' ];

		$itemtypes = App\ItemType::pluck('name','id');
		$units = $units + App\Unit::pluck('abbreviation','abbreviation')->toArray();

		return view('inventory.item.create')
					->with('itemtypes',$itemtypes)
					->with("units",$units)
					->with('title','Inventory');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		/**
		 * initialize the values
		 * fetched from user
		 * @var [type]
		 */
		$brand = $this->sanitizeString($request->get('brand'));
		$itemtype = $this->sanitizeString($request->get('itemtype'));
		$model = $this->sanitizeString($request->get('model'));
		$quantity = $this->sanitizeString($request->get('quantity'));
		$unit = $this->sanitizeString($request->get('unit'));
		$details = $this->sanitizeString($request->get('details'));
		$_receipt = $this->sanitizeString($request->get('receipt'));

		$receipt = new App\Receipt;

		/**
		 * validator for receipt number
		 * @var [type]
		 */
		$validator = Validator::make([
				'Receipt Number' => $_receipt
			], $receipt->inventoryRules());

		if($validator->fails())
		{
			return redirect('inventory/create')
				->withInput()
				->withErrors($validator);
		}

		/**
		 * validate the inventory
		 * @var [type]
		 */
		$validator = Validator::make([
				'Item Type' => $itemtype,
				'Brand' => $brand,
				'Model' => $model,
				'Details' => $details,
				'Unit' => $unit,
				'Quantity' => $quantity,
				'Profiled Items' => 0
			],App\Inventory::$rules);

		if($validator->fails())
		{
			return redirect('inventory/create')
				->withInput()
				->withErrors($validator);
		}

		DB::beginTransaction();

		/**
		 * check if the receipt already exists,
		 * create if not
		 * @var [type]
		 */
		$receipt = App\Receipt::firstOrCreate([
			'number' => $_receipt
		]);

		$itemtype = App\ItemType::find($itemtype);

		/**
		 * check if the inventory exists in database
		 * fetch the first item it found
		 * @var [type]
		 */
		$inventory = App\Inventory::locate($brand, $model, $itemtype)->first();
		$unit = App\Unit::findByAbbreviation($unit)->first();

		/**
		 * if the items exists, use the existing items
		 * @var App
		 */
		if( !isset($inventory) || $inventory->count() <= 0 ) 
			$inventory = new App\Inventory;

		/**
		 * set all the values before sending to database
		 * @var [type]
		 */
		$inventory->code = App\Inventory::generateCode();
		$inventory->brand = $brand;
		$inventory->itemtype_id = $itemtype->id; 
		$inventory->model = $model;
		$inventory->unit_name = $unit->name;
		$inventory->details = $details;
		$inventory->user_id = Auth::user()->id;
		$inventory->save();
		$inventory->log($quantity, $_receipt);

		/**
		 * insert the values in the pivot table
		 * @var [type]
		 */
		$inventory->receipts()->syncWithoutDetaching(array(
			$receipt->id => [
				'received_quantity' => $quantity,
				'profiled_items' => 0
			]
		));

		DB::commit();

		Session::flash('success','Items added to Inventory');

		/**
		 * redirect to profiling
		 */
		if($request->has('redirect-profiling'))
		{
			return redirect("item/profile/create?id=$inventory->id");
		}

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

		if($id == 'search')
		{
			return $this->searchView();
		}


		if($request->ajax())
		{
			$inventory = App\Inventory::where('id','=',$id)
								->with('itemtype')
								->first();
			return json_encode( $inventory );
		}

		return view('inventory.item.show');
	}

	public function showLogs(Request $request, $id)
	{
		$inventory = App\Inventory::with('logs')->find($id);

		if($request->ajax())
		{
			return datatables($inventory->logs)->toJson();
		}

		return view('inventory.show')
				->with('inventory', $inventory);
	}

	public function edit($id)
	{
		$inventory = App\Inventory::find($id);

		return view('inventory.item.edit')
				->with('inventory',$inventory);
	}

	public function update(Request $request, $id)
	{

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

		Session::flash('success-message','Inventory content updated');
		return redirect('inventory');

	}

	public function release(Request $request)
	{
		$quantity = $request->get('quantity');
		$purpose = $request->get('purpose');
		$id = $request->get('id');

		$validator = Validator::make([
				'quantity' => $quantity,
				'purpose' => $purpose,
				'inventory' => $id,
			], App\Inventory::$releaseRules);

		if($validator->fails())
		{

			Session::flash('show-modal', true);
			return back()->withErrors($validator)->withInput();
		}

		$inventory = App\Inventory::find($id);

		if( abs($inventory->quantity) < abs($quantity) )
		{

			Session::flash('show-modal', true);
			return back()->withErrors([
								'Insufficient balance to release'
							])->withInput();
		}

		$inventory->log( $quantity * -1, $purpose);

		Session::flash('success-message', "$quantity Item Released");
		return redirect("inventory/$id/log");
	}

	public function importView()
	{
		return view('inventory.item.import');
	}

	public function import()
	{
		$file = $request->file('file');
		// $filename = str_random(12);
		//$filename = $file->getClientOriginalName();
		//$extension =$file->getClientOriginalExtension();
		$filename = 'inventory.'.$file->getClientOriginalExtension();
		$destinationPath = public_path() . '\files';
		$file->move($destinationPath, $filename);

		$excel = Excel::load($destinationPath . "/" . $filename, function($reader) {

		    // reader methods

		})->get();


		return $excel;
		Session::flash('success-message','Items Imported to Inventory');
		return redirect('inventory/view/import');
	}

	public function getBrands(Request $request)
	{
		if($request->ajax())
		{
			$brand = $this->sanitizeString($request->get('term'));
			$inventory = App\Inventory::where('brand','like','%'.$brand.'%')
										->distinct()
										->pluck('brand');
			return json_encode( $inventory );
		}
	}

	public function getModels(Request $request)
	{
		if($request->ajax())
		{
			$model = $this->sanitizeString($request->get('term'));
			$inventory = App\Inventory::where('model','like','%'.$model.'%')->distinct()->pluck('model');
			return json_encode( $inventory );
		}
	}

	public function searchView()
	{
		$brand = App\Inventory::distinct('brand')->pluck('brand','brand');
		$model = App\Inventory::distinct('brand')->pluck('model','model');
		$itemtype = App\ItemType::distinct()->pluck('name','name');
		return view('inventory.item.search')
					->with('brand',$brand)
					->with('model',$model)
					->with('itemtype',$itemtype)
					->with('inventory',[]);
	}

	public function search(Request $request)
	{
		// return $request->all();
		$keyword = $this->sanitizeString($request->get('keyword'));
		$total = $this->sanitizeString($request->get('total'));
		$brand = $this->sanitizeString($request->get('brand'));
		$model = $this->sanitizeString($request->get('model'));
		$itemtype = $this->sanitizeString($request->get('itemtype'));
		$profiled = $this->sanitizeString($request->get('profiled'));

		$inventory = new App\Inventory;

		if($this->hasData($keyword))
		{
			$inventory = $inventory->where(function($query) use ($keyword){
				$query->where('brand','like','%'.$keyword.'%')
						->orWhere('model','like','%'.$keyword.'%')
						->orWhere('details','like','%'.$keyword.'%')
						->orWhere('warranty','like','%'.$keyword.'%')
						->orWhere('unit','like','%'.$keyword.'%')
						->orWhere('quantity','like','%'.$keyword.'%')
						->orWhere('profileditems','like','%'.$keyword.'%');
			});
		}

		if($request->get('include-total') == 'on')
		{
			$inventory = $inventory->where('quantity','like','%'.$total.'%');
		}

		if($request->get('include-profiled') == 'on')
		{
			$inventory = $inventory->where('quantity','like','%'.$profiled.'%');
		}

		if($request->get('include-brand') == 'on')
		{
			$inventory = $inventory->where('quantity','like','%'.$brand.'%');
		}

		if($request->get('include-model') == 'on')
		{
			$inventory = $inventory->where('quantity','like','%'.$model.'%');
		}

		if($request->get('include-itemtype') == 'on')
		{
			$inventory = $inventory->where('quantity','like','%'.$itemtype.'%');
		}

		$count = $inventory->count();

		Session::flash('success-message',"Search Result: $count");

		$brand = App\Inventory::distinct('brand')->pluck('brand','brand');
		$model = App\Inventory::distinct('brand')->pluck('model','model');
		$itemtype = App\ItemType::distinct()->pluck('name','name');
		return view('inventory.item.search')
					->with('brand',$brand)
					->with('model',$model)
					->with('itemtype',$itemtype)
					->with('inventory',$inventory->get());

	}
}