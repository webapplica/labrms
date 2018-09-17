<?php

namespace App\Http\Controllers\Maintenance;

use Session;
use Carbon\Carbon;
use App\AcademicYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Classes\Requests\Maintenance\AcademicYearRequest;

class AcademicYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return datatables(AcademicYear::all())->toJson();
        }

        return view('academicyear.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('academicyear.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $start = $this->sanitizeString(Input::get('date_started'));
        $end = $this->sanitizeString(Input::get('end'));

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        $validator = Validator::make([
            'Date Started' =>$start,
            'Date Ended' => $end
        ],AcademicYear::$rules);

        if($validator->fails()) {
            Session::flash('error-message','Invalid Information Received');
            return redirect('academicyear');
        }

        $academicyear = new AcademicYear;
        $academicyear->name = $start->year . "-" . $end->year;
        $academicyear->start = $start;
        $academicyear->end = $end;
        $academicyear->save();

        Session::flash('success-message','Academic Year Added');
        return redirect('academicyear');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

        if($request->ajax())
        {
            $id = $this->sanitizeString(Input::get('id'));
            $start = $this->sanitizeString(Input::get('start'));
            $end = $this->sanitizeString(Input::get('end'));

            $start = Carbon::parse($start);
            $end = Carbon::parse($end);

            $validator = Validator::make([
                'Date Started' =>$start,
                'Date Ended' => $end
            ],AcademicYear::$rules);

            if($validator->fails())
            {
                Session::flash('error-message','Invalid Information Received');
                return redirect('academicyear');
            }

            $academicyear = AcademicYear::find($id);
            $academicyear->name = $start->year . "-" . $end->year;
            $academicyear->start = $start;
            $academicyear->end = $end;
            $academicyear->save();

        }

        $id = $this->sanitizeString(Input::get('id'));
        $start = $this->sanitizeString(Input::get('start'));
        $end = $this->sanitizeString(Input::get('end'));

        $start = Carbon::parse($start);
        $end = Carbon::parse($end);

        $validator = Validator::make([
            'Date Started' =>$start,
            'Date Ended' => $end
        ],AcademicYear::$rules);

        if($validator->fails())
        {
            Session::flash('error-message','Invalid Information Received');
            return redirect('academicyear');
        }
        
        $academicyear = AcademicYear::find($id);
        $academicyear->name = $start->year . "-" . $end->year;
        $academicyear->start = $start;
        $academicyear->end = $end;
        $academicyear->save();

        Session::flash('success-message','Academic Year Updated');
        return redirect('academicyear');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($request->ajax())
        {
            $academicyear = AcademicYear::find($id);
            $academicyear->delete();
            return json_encode('success');
        }

        $academicyear = AcademicYear::find($id);
        $academicyear->delete();

        Session::flush('success-message','Academic Year removed');
        return redirect('academicyear');
    }
}
