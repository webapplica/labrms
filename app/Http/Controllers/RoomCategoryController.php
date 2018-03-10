<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;
use Session;
use App;
use Illuminate\Http\Request;

class RoomCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $category = App\RoomCategory::all();
            return datatables($category)->toJson();
        }

        return view('room.category.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('room.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $name = $this->sanitizeString($request->get('name'));

        $category = new App\RoomCategory;

        $validator = Validator::make([
            'Category Name' => $name
        ], $category->rules());

        if($validator->fails())
        {
            return redirect('room/category')
                    ->withInput()
                    ->withErrors($validator);
        }

        $category->name = $name;
        $category->save();

        Session::flash('success-message','Room Category added');
        return redirect('room/category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return view('room.category.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        return view('room.category.edit');
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

        $name = $this->sanitizeString($request->get('name'));
        $category = App\RoomCategory::find($id);

        $validator = Validator::make([
            'Category' => $id,
            'Category Name' => $name
        ], $category->updateRules());

        if($validator->fails())
        {
            if($request->ajax())
            {
                return response()->json([
                    'Operation' => false,
                    'errors' => $validator
                ], 200);
            }

            return redirect('room/category')
                    ->withInput()
                    ->withErrors($validator);
        }

        $category->name = $name;
        $category->save();

        if($request->ajax())
        {
            return response()->json([
                'Operation' => true
            ], 200);
        }

        Session::flash('success-message','Room Category updated');
        return redirect('room/category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $category = App\RoomCategory::find($id);

        $validator = Validator::make([
            'Category' => $id
        ], $category->deleteRules());

        if($validator->fails())
        {
            if($request->ajax())
            {
                return response()->json([
                    'Operation' => false,
                    'errors' => $validator
                ], 200);
            }

            return redirect('room/category')
                    ->withInput()
                    ->withErrors($validator);
        }

        $category->delete();

        if($request->ajax())
        {
            return response()->json([
                'Operation' => true
            ], 200);
        }

        Session::flash('success-message','Room Category removed');
        return redirect('room/category');
    }
}
