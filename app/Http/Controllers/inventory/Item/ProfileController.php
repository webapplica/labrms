<?php

namespace App\Http\Controllers\inventory\item;

use App\Models\Receipt;
use App\Models\Room\Room;
use Illuminate\Http\Request;
use App\Models\Inventory\Inventory;
use App\Http\Controllers\Controller;
use App\Commands\Inventory\Profiling\BatchProfiling;

class ProfileController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        $inventory = Inventory::with('receipts')->findOrFail($id);
        $receipts = $inventory->receipts->pluck('number', 'id');
        $locations = Room::pluck('name', 'id');
        $unprofiled_items_count = $inventory->unprofiled;

        return view('inventory.profile.create', compact('inventory', 'receipts', 'locations', 'unprofiled_items_count'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->dispatch(new BatchProfiling($request, $id));
        return redirect('inventory/' . $request->id . '/profile')->with('success-message', __('tasks.success'));
    }
}
