<?php

namespace App\Http\Controllers\inventory\item;

use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Commands\Inventory\ReleaseInventory;

class ReleaseController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $inventory = Inventory::findOrFail($id);
        return view('inventory.item.release', compact('inventory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->dispatch(new ReleaseInventory($request, $id));
        return redirect('inventory')->with('success-message', __('tasks.success'));
    }
}
