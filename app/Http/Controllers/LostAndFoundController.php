<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Validator;
use Session;
use DB;
use App;
use Illuminate\Http\Request;

class LostAndFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( $request->ajax())
        {
            $items = App\LostItem::all();
            return datatables($items)->toJson();
        }

        return view("lostandfound.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("lostandfound.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $identifier = $this->sanitizeString($request->get('identifier'));
        $description = $this->sanitizeString($request->get('description'));
        $datefound = $this->sanitizeString($request->get("datefound"));

        $datefound = Carbon::parse($datefound);

        $validator = Validator::make([
            'Identifier' => $identifier,
            'Description' => $description,
            'Date Found' => $datefound
        ],App\LostItem::$rules);

        if($validator->fails())
        {
            return redirect('lostandfound/create')
                    ->withInput()
                    ->withErrors($validator);
        }

        $item = new App\LostItem;
        $item->identifier = $identifier;
        $item->description = $description;
        $item->date_found = $datefound;
        $item->save();

        Session::flash('success-message','Item added to lost and found');
        return redirect('lostandfound');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('lostandfound.show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $validator = Validator::make([
                'Record' => $id
            ], App\LostItem::$isExisting);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }

        $lostandfound = App\LostItem::find($id);
        return view('lostandfound.update')
                ->with('lostandfound',$lostandfound);
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

        if($request->ajax())
        {
            if($request->has('claim'))
            {
                $claimant = $this->sanitizeString($request->get('claimant'));

                $dateclaimed = Carbon::now();
                $status = 'claimed';

                $validator = Validator::make([
                    'Claimant' => $claimant,
                    'ID' => $id
                ],App\LostItem::$claimRules);

                if($validator->fails())
                {
                    return response()->json([
                            'success' => false,
                            'error' => $errors
                        ], 500);
                }

                $item = App\LostItem::find($id);
                $item->status = $status;
                $item->date_claimed = $dateclaimed;
                $item->claimant = $claimant;
                $item->save();

                return response()->json([
                        'success' => true
                    ], 200);
            }
        }

        $identifier = $this->sanitizeString($request->get('identifier'));
        $description = $this->sanitizeString($request->get('description'));
        $datefound = $this->sanitizeString($request->get("datefound"));

        $datefound = Carbon::parse($datefound);

        $validator = Validator::make([
            'Identifier' => $identifier,
            'Description' => $description,
            'Date Found' => $datefound
        ],App\LostItem::$updateRules);

        if($validator->fails())
        {
            return redirect("lostandfound/$id/edit")
                    ->withInput()
                    ->withErrors($validator);
        }

        $item = App\LostItem::find($id);
        $item->identifier = $identifier;
        $item->description = $description;
        $item->date_found = $datefound;
        $item->save();

        Session::flash('success-message','Item information updated');
        return redirect('lostandfound');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {

        $item = new App\LostItem;

        $validator = Validator::make([
            ''
        ], $item->rules() );

        $item = App\LostItem::find($id);
        $item->delete();

        if($request->ajax())
        {
            return response()->json([
                    'success' => true
                ], 200);
        }

        Session::flash('success-message','Item removed');
        return redirect('lostandfound');
    }
}
