<?php

namespace App\Commands\Workstation;

use App\Models\Item\Item;

class AssembleWorkstation
{

	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		
		$systemunit = $request->systemunit;
		$monitor = $request->monitor;
		$avr = $request->avr;
		$keyboard = $request->keyboard;
		$oskey = $request->os;
		$mouse = $request->mouse;
		$name = $request->name;

		DB::beginTransaction();

		$items = Item::with('inventory', 'type')
					->propertyNumber($systemunit)
					->orPropertyNumber($monitor)
					->orPropertyNumber($keyboard)
					->orPropertyNumber($avr)
					->orLocalId($mouse)
					->get();

		$workstation->oskey = $oskey;
		$workstation->systemunit_id = $item->propertyNumber($systemunit)->pluck('id')->first;
		$workstation->monitor_id =  $item->propertyNumber($monitor)->pluck('id')->first;
		$workstation->avr_id =  $item->propertyNumber($avr)->pluck('id')->first;
		$workstation->keyboard_id =  $item->propertyNumber($keyboard)->pluck('id')->first;
		$workstation->mouse_id =  $item->localId($mouse)->pluck('id')->first;;
		$workstation->assemble();

		DB::commit();
	}
}