<?php

namespace App\Http\Controllers\inventory\item;

use App\Models\Item\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::with('inventory', 'tickets')->findOrFail($id);
        $tickets = $item->tickets->sortByDesc('created_at');

        return view('inventory.profile.show', compact('item', 'tickets'));
    }
}
