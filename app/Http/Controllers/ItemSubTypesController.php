<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;

class ItemSubTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Request::ajax())
        {
            return json_encode([
                'data' => App\ItemSubType::all()
            ]);
        }

        return view('item.subtype.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('item.subtype.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        $name = $this->sanitizeString(Input::get('name'));

        $validator = Validator::make([
            'Name' => $name
        ],App\ItemSubType::$rules);

        if($validator->fails())
        {
            return redirect('item/subtype')
                    ->withInput()
                    ->withErrors($validator);
        }

        $itemsubtype = new App\ItemSubType;
        $itemsubtype->name = $name;
        $itemsubtype->save();

        Session::flash('success-message','Item Sub Type added');
        return redirect('item/subtype');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('item.subtype.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('item.subtype.edit');
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
        if(Request::ajax())
        {
            $name = $this->sanitizeString(Input::get('name'));
            $id = $this->sanitizeString($id);

            $validator = Validator::make([
                'Name' => $name
            ],App\ItemSubType::$updateRules);

            if($validator->fails())
            {
                return redirect('item/subtype')
                        ->withInput()
                        ->withErrors($validator);
            }

            $itemsubtype = App\ItemSubType::find($id);
            $itemsubtype->name = $name;
            $itemsubtype->save();

            return json_encode('success');
        }

        $name = $this->sanitizeString(Input::get('name'));

        $validator = Validator::make([
            'Name' => $name
        ],App\ItemSubType::$rules);

        if($validator->fails())
        {
            return redirect('item/subtype')
                    ->withInput()
                    ->withErrors($validator);
        }

        $itemsubtype = App\ItemSubType::find($id);
        $itemsubtype->name = $name;
        $itemsubtype->save();

        Session::flash('success-message','Item Sub Type updated');
        return redirect('item/subtype');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Request::ajax())
        {
            $itemsubtype = App\ItemSubType::find($id);
            $itemsubtype->delete();
            return json_encode('success');
        }

        $itemsubtype = App\ItemSubType::find($id);
        $itemsubtype->delete();

        Session::flash('success-message','Item Sub Type removed');
        return redirect('item/subtype');
    }
}
