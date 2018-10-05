<?php

namespace App\Http\Controllers\inventory\item;

use App\Models\Unit;
use App\Models\Item\Type;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Commands\Inventory\AddInventory;

class ReceiveController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
		$types = Type::pluck('name','id');
        $units = Unit::pluck('abbreviation', 'id')->toArray();
        $inventory = Inventory::findOrFail($id);

		return view('inventory.item.create', compact('types', 'units', 'inventory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$this->dispatch(new AddInventory($request));
		return redirect('inventory')->with('success-message', __('tasks.success'));
    }
}
