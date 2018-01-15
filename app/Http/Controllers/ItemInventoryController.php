<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App;
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
			return json_encode([
					'data' => App\Inventory::with('itemtype')
									->get()
					]);
		}
		
		$brand = null;
		$model = null;
		$itemtype = null;
		$itemsubtype = null;
		$units = [ null => 'None' ];
		$receipts = [ null => 'None' ];

		$itemsubtypes = App\ItemSubType::pluck('name','id');
		$itemtypes = App\ItemType::pluck('name','id');
		$units = $units + App\Unit::pluck('abbreviation','abbreviation')->toArray();
		$itemtypes = App\ItemType::category('equipment')->pluck('name','id');
		$receipts = $receipts +  App\Receipt::pluck('number','number')->toArray();

		return view('inventory.item.index')
						->with('title','Inventory')
						->with('itemtypes',$itemtypes)
						->with('brand',$brand)
						->with('model',$model)
						->with('itemtype',$itemtype)
						->with('itemsubtype',$itemsubtype)
						->with('itemtypes',$itemtypes)
						->with("units",$units)
						->with('itemsubtypes',$itemsubtypes)
						->with('receipts',$receipts);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		$brand = null;
		$model = null;
		$itemtype = null;
		$itemsubtype = null;
		$units = [ null => 'None' ];
		$receipts = [ null => 'None' ];

		$itemsubtypes = App\ItemSubType::pluck('name','id');
		$itemtypes = App\ItemType::pluck('name','id');
		$units = $units + App\Unit::pluck('abbreviation','abbreviation')->toArray();
		$itemtypes = App\ItemType::category('equipment')->pluck('name','id');
		$receipts = $receipts +  App\Receipt::pluck('number','number')->toArray();

		if($request->has('brand'))
		{
			$brand = $this->sanitizeString($request->get('brand'));
		}

		if($request->has('model'))
		{
			$model = $this->sanitizeString($request->get('model'));
		}

		if($request->has('itemtype'))
		{
			$itemtype = $this->sanitizeString($request->get('itemtype'));
		}

		if($request->has('itemsubtype'))
		{
			$itemsubtype = $this->sanitizeString($request->get('itemsubtype'));
		}

		return view('inventory.item.create')
					->with('itemtypes',$itemtypes)
					->with('brand',$brand)
					->with('model',$model)
					->with('itemtype',$itemtype)
					->with('itemsubtype',$itemsubtype)
					->with('itemtypes',$itemtypes)
					->with("units",$units)
					->with('itemsubtypes',$itemsubtypes)
					->with('receipts',$receipts)
					->with('title','Inventory::add');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		//inventory
		$brand = $this->sanitizeString($request->get('brand'));
		$itemtype = $this->sanitizeString($request->get('itemtype'));
		$itemsubtype = $this->sanitizeString($request->get('itemsubtype'));
		$model = $this->sanitizeString($request->get('model'));
		$quantity = $this->sanitizeString($request->get('quantity'));
		$unit = $this->sanitizeString($request->get('unit'));
		$details = $this->sanitizeString($request->get('details'));
		$receipt = $this->sanitizeString($request->get('receipt'));

		//validator
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
			return redirect('inventory/item/create')
				->withInput()
				->withErrors($validator);
		}

		$receipt = App\Receipt::findByNumber($receipt);
		$itemtype = App\ItemType::find($itemtype);

		$inventory = new App\Inventory;
		$inventory->brand = $brand;
		$inventory->itemtype_id = $itemtype->id;
		$inventory->itemsubtype_id = null;
		$inventory->model = $model;
		$inventory->unit = $unit;
		$inventory->details = $details;
		$inventory->save();

		$inventory->receipts()->syncWithoutDetaching(array(
			$receipt->id => [
				'received_quantity' => $quantity,
				'profiled_items' => $quantity
			]
		));

		Session::flash('success','Items added to Inventory');

		if($request->has('redirect-profiling'))
		{
			return redirect("item/profile/create?id=$inventory->id");
		}

		return redirect('inventory/item');
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
			return json_encode(
				App\Inventory::where('id','=',$id)
								->with('itemtype')
								->first()
			);
		}

		return view('inventory.item.show');
	}

	public function edit($id)
	{
		try
		{
			$inventory = App\Inventory::find($id);
			return view('inventory.item.edit')
					->with('inventory',$inventory);
		} catch ( Exception $e ) {
			Session::flash('success-message','Problems occur while sending your data to the server');
			return redirect('inventory/item');
		}
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
			return redirect("inventory/item/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		try {

			$inventory = App\Inventory::find($id);
			$inventory->brand = $brand;
			$inventory->model = $model;
			$inventory->itemtype_id = $itemtype;
			$inventory->details = $details;
			$inventory->warranty = $warranty;
			$inventory->unit = $unit;
			$inventory->save();
		} catch(Exception $e) {
			Session::flash('error-message','Unknown Error Encountered');
			return redirect('inventory/item');
		}

		Session::flash('success-message','Inventory content updated');
		return redirect('inventory/item');

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
		return redirect('inventory/item/view/import');
	}

	public function getBrands(Request $request)
	{
		if($request->ajax())
		{
			$brand = $this->sanitizeString($request->get('term'));
			return json_encode(
				App\Inventory::where('brand','like','%'.$brand.'%')->distinct()->pluck('brand')
			);
		}
	}

	public function getModels(Request $request)
	{
		if($request->ajax())
		{
			$model = $this->sanitizeString($request->get('term'));
			return json_encode(
				App\Inventory::where('model','like','%'.$model.'%')->distinct()->pluck('model')
			);
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
