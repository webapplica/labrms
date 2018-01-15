<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ItemTypesController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{
			return json_encode([
					'data' => App\ItemType::all()
				]);
		}

		return view('item.type.index')
			->with('category',App\ItemType::$category);
	}

	public function create()
	{
		return view('item.type.create')
			->with('category',App\ItemType::$category)
			->with('title',"Item Type::Create");
	}

	public function show($id)
	{
		return view('errors.404');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$name = $this->sanitizeString(Input::get('name'));
		$description = $this->sanitizeString(Input::get('description'));
		$category = $this->sanitizeString(Input::get('category'));

		$itemtype = new App\ItemType;

		$validator = Validator::make([
			'name' => $name,
			'description' => $description
		],$itemtype->rules());

		if($validator->fails())
		{
			return redirect('item/type/create')
				->withInput()
				->withErrors($validator);
		}

		$itemtype->name = $name;
		$itemtype->description = $description;
		$itemtype->category = $category;
		$itemtype->save();

		Session::flash('success-message','Item type created');
		return redirect('item/type');
	}

	public function edit($id)
	{
		$validator = Validator::make([
			'id' => $id,
		],App\ItemType::$existInTableRules);

		if($validator->fails())
		{
			Session::flash("error-message","Item does not exist");
			return view('errors.404');
		}

		$itemtype = App\Itemtype::find($id);
		return view('item.type.edit')
			->with('itemtype',$itemtype)
			->with('category',App\ItemType::$category)
			->with('title',"Item Type::$itemtype->name");
	}

	public function update($id)
	{
		$name = $this->sanitizeString(Input::get('name'));
		$description = $this->sanitizeString(Input::get('description'));
		$category = $this->sanitizeString(Input::get('category'));

		$itemtype = App\ItemType::find($id);

		$validator = Validator::make([
			'name' => $name,
			'description' => $description
		], $itemtype->updateRules());

		if($validator->fails())
		{
			return redirect("item/type/$id/edit")
				->withInput()
				->withErrors($validator);
		}

		$itemtype->name = $name;
		$itemtype->description = $description;
		$itemtype->category = $category;
		$itemtype->save();

		Session::flash('success-message','Item type updated');
		return redirect('item/type');
	}

	public function destroy($id)
	{
		$validator = Validator::make([
			'id' => $id,
		],App\ItemType::$existInTableRules);

		if($validator->fails())
		{
			Session::flash("error-message","Item does not exist");
			return view('errors.404');
		}

		if(Request::ajax())
		{
				$itemtype = App\Itemtype::find($id);
				$itemtype->delete();
				return json_encode('success');
		}

		$itemtype = App\Itemtype::find($id);
		$itemtype->delete();

		Session::flash('success-message','Item type deleted');
		return redirect('item/type/');

	}

	public function getAllItemTypes()
	{
		if(Request::ajax())
		{
			$workstation = Input::get('workstation');
			if($workstation === 'workstation')
			{
				$itemtype = App\ItemType::whereNotIn('name',['System Unit','Display','AVR','Keyboard'])->get();
			}
			else
			{
				$itemtype = App\ItemType::all();
			}
			return json_encode($itemtype);
		}
	}

	public function getItemTypesForEquipmentInventory()
	{
		if(Request::ajax())
		{
			$itemtype = App\ItemType::category('equipment')->get();
			return json_encode($itemtype);
		}

	}

	public function getItemTypesForSuppliesInventory()
	{
		if(Request::ajax())
		{
			$itemtype = App\ItemType::category('supply')->get();
			return json_encode($itemtype);
		}

	}

}
