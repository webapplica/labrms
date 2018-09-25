<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Unit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UnitRequest\UnitStoreRequest;
use App\Http\Requests\UnitRequest\UnitUpdateRequest;

class UnitController extends Controller
{
    protected $viewBasePath = 'maintenance.unit.';

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

        return view($this->viewBasePath . 'index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view($this->viewBasePath . 'create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitStoreRequest $request)
    {
        $this->dispatch(new NewUnit($request));
        return redirect('unit')->with('success-message', __('tasks.success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $unit = App\Unit::find($id);

        if( count($unit) <= 0 )
        {
        Session::flash('success-message','Invalid Unit Information');
            return redirect('unit');
        }

        return view( $this->viewBasePath . 'edit')
                ->with('title','Unit')
                ->with('unit',$unit);
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
        $name = $this->sanitizeString($request->get('name'));
        $description = $this->sanitizeString($request->get("description"));
        $abbreviation = $this->sanitizeString($request->get("abbreviation"));

        $validator = Validator::make([
            'Name' => $name,
            'Description' => $description,
            'Abbreviation' => $abbreviation
        ],App\Unit::$updateRules);

        if($validator->fails())
        {
            return redirect()->back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $unit = App\Unit::find($id);
        $unit->name = $name;
        $unit->description = $description;
        $unit->abbreviation = $abbreviation;
        $unit->save();

       Session::flash('success-message','Unit Information Updated');
        return redirect('unit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->ajax())
        {
            $unit = App\Unit::find($id);

            if(count($unit) <= 0)
            {
                return json_encode('error');
            }

            $unit->delete();
            return json_encode('success');
        }

        $unit = App\Unit::find($id);
        $unit->delete();

       Session::flash('success-message','Unit Removed');
        return redirect('unit');
    }
}
