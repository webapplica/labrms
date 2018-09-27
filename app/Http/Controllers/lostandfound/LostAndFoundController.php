<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item\Lost as LostItem;
use App\Commands\Item\LostItem\AddItem;
use App\Commands\Item\LostItem\UpdateItem;

class LostAndFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->ajax()) {
            $items = LostItem::all();
            return datatables($items)->toJson();
        }

        return view("lostandfound.item.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("lostandfound.item.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->dispatch(new AddItem($request));
        return redirect('lostandfound')->with('success-message', __('tasks.success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $item = LostItem::findOrFail($id);
        return view('lostandfound.item.update')
                ->with('item', $item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->dispatch(new UpdateItem($request, $id));
        return redirect('lostandfound')->with('success-message', __('tasks.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        LostItem::findOrFail($id)->delete();
        return redirect('lostandfound')->with('success-message', __('tasks.success'));
    }
}
