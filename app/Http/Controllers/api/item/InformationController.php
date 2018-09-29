<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InformationController extends Controller 
{

	/**
	*
	*	get all item brand
	*
	*	@param itemtype
	*	@return item brand
	*
	*/
    public function getAllBrands(Request $request)
    {
		if($request->ajax()) {
            $inventoryBrands = Inventory::select('brand')->get();
			return json_encode($inventoryBrands);
		}
	}

	/**
	*
    *	get all item model
    *
	*	@param brand
	*	@return item model
	*
	*/
    public function getAllModels(Request $request)
    {
		if($request->ajax()) {
            $models = Inventory::select('model')->get();
			return json_encode($models);
		}
	}

	/**
	*
	*	get unassigned system unti
	*	uses ajax request
	*	@return lists of property number
	*
	*/
    public function getUnassignedSystemUnit(Request $request)
    {
		if($request->ajax()) {
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
    public function getUnassignedMonitor(Request $request)
    {
		if($request->ajax()) {
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
    public function getUnassignedAVR(Request $request)
    {
		if($request->ajax()) {
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
    public function getUnassignedKeyboard(Request $request)
    {
		if($request->ajax()) {
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
    public function getAllPropertyNumber(Request $request)
    {
		if($request->ajax()) {
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

		if($request->ajax())
		{
			try{
				$item = App\Item::with('inventory.itemtype')
										->propertyNumber($propertynumber)
										->first();

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

		if($request->ajax())
		{
			$monitor = $this->sanitizeString($request->get('term'));

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

		if($request->ajax())
		{
			$keyboard = $this->sanitizeString($request->get('term'));

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

		if($request->ajax())
		{
			$avr = $this->sanitizeString($request->get('term'));

	
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

		if($request->ajax())
		{
			$systemunit = $this->sanitizeString($request->get('term'));

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

		if($request->ajax())
		{
			$systemunit = $this->sanitizeString($request->get('term'));
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
}
