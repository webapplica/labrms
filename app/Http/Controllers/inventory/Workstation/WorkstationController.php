<?php

namespace App\Http\Controllers\Inventory\Workstation;

use App\Models\Item\Item;
use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Http\Modules\Generator\ListGenerator;
use App\Commands\Workstation\UpdateWorkstation;
use App\Commands\Workstation\AssembleWorkstation;
use App\Http\Requests\WorkstationRequest\WorkstationStoreRequest;
use App\Http\Requests\WorkstationRequest\WorkstationUpdateRequest;

class WorkstationController extends Controller 
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax()) {
			$workstations = Workstation::all();
			return datatables($workstations)->toJson();
		}

		return view('workstation.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
		
		$systemunits = Item::nameOfType(Type::SYSTEMUNIT)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		$monitors = Item::nameOfType(Type::MONITOR)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		$keyboards = Item::nameOfType(Type::KEYBOARD)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		$avrs = Item::nameOfType(Type::AVR)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		return view('workstation.create', compact(
			'systemunits', 'monitors', 'keyboards', 'avrs'
		));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(WorkstationStoreRequest $request)
	{
		$this->dispatch(new AssembleWorkstation($request));	
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{

		$workstation = Workstation::findOrFail($id);
		if($request->ajax()) {
			return datatables($workstation->tickets)->toJson();
		}

		return view('workstation.show', compact('workstation'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Request $request, $id)
	{
		$workstation = Workstation::with('systemunit', 'monitor', 'mouse', 'keyboard', 'avr')->findOrFail($id);
		
		$systemunits = Item::nameOfType(Type::SYSTEMUNIT)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		$monitors = Item::nameOfType(Type::MONITOR)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		$keyboards = Item::nameOfType(Type::KEYBOARD)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();

		$avrs = Item::nameOfType(Type::AVR)
						->notAssembledInWorkstation()
						->select('id', 'local_id')->get();
						
		return view('workstation.edit', compact(
			'workstation', 'systemunits', 'monitors', 'keyboards', 'avrs'
		));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(WorkstationUpdateRequest $request, $id)
	{
		$this->dispatch(new UpdateWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
	{
		$this->dispatch(new DisassembleWorkstation($request, $id));
		return redirect('workstation')->with('success-message', __('tasks.success'));
	}
}
