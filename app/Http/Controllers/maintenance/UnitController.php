<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Commands\Unit\AddUnit;
use App\Commands\Unit\UpdateUnit;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest\UnitStoreRequest;
use App\Http\Requests\UnitRequest\UnitUpdateRequest;

class UnitController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return datatables(Unit::all())->toJson();
        }

        return view('maintenance.unit.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenance.unit.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitStoreRequest $request)
    {
        $this->dispatch(new AddUnit($request));
        return redirect('unit')->with('success-message', __('tasks.success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = Unit::findOrFail($id);
        return view('maintenance.unit.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnitUpdateRequest $request, $id)
    {
        $this->dispatch(new UpdateUnit($request, $id));
        return redirect('unit')->with('success-message', __('tasks.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        Unit::findOrFail($id)->delete();
        return redirect('unit')->with('success-message', __('tasks.success'));
    }
}
