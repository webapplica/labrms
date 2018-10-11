<?php

namespace App\Commands\Workstation;

use App\Models\Room\Room;
use App\Models\Item\Item;
use App\Http\Modules\Generator\Code;
use App\Models\Workstation\Workstation;

class AssembleWorkstation
{

	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function handle()
	{
		
		// assign global request to a local request variable
		// for handling easily
		$request = $this->request;

		// assign the values from the request class into the 
		// specific variables
		$systemunit = $request->systemunit;
		$monitor = $request->monitor;
		$avr = $request->avr;
		$keyboard = $request->keyboard;
		$oskey = $request->os;
		$mouse = $request->mouse;
		$name = $request->name;
		
		// check if the room exists and find the room with the 
		// corresponding name
		$room = Room::name($request->location)->first();

		// generate a code for the workstation name
		// use a custom package designed specifically to
		// generate a code
		$code = Code::make([
			config('app.workstation_id'),
			isset($room->name) ? $room->name : 'TMP',
			Workstation::count() + 1,
		], Code::DASH_SEPARATOR);

		// use transaction in order to change the record properly
		DB::beginTransaction();

		// list all the items the workstation has
		$items = Item::with('inventory', 'type')
						->inPropertyNumbers([
							$systemunit, $monitor, $keyboard, $avr, $mouse
						])->get();

		// create a record of workstation and store in variable workstation
		// use the variable code and items. find the specific item for the 
		// specific row and return the id for the said item
		$workstation = Workstation::create([
			'oskey' => $oskey,
			'systemunit_id' => $item->propertyNumber($systemunit)->pluck('id')->first(),
			'monitor_id' => $item->propertyNumber($monitor)->pluck('id')->first(),
			'avr_id' => $item->propertyNumber($avr)->pluck('id')->first(),
			'keyboard_id' => $item->propertyNumber($keyboard)->pluck('id')->first(),
			'mouse_id' => $item->localId($mouse)->pluck('id')->first(),
			'name' => $code
		]);

		// create a workstation ticket to record the changes in the ticket
		// for each item, create an item ticket to record assembly on the said item

		// end the transaction, commit the query
		DB::commit();
	}
}