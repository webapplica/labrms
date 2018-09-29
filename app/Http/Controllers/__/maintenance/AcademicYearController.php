<?php

namespace App\Http\Controllers\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $this->dispatch(new AddAcademicYear());
        return redirect('academicyear')->with('success-message', __('tasks.success'));
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
        $this->dispatch(new UpdateAcademicYear());
        return redirect('academicyear')->with('success-message', __('tasks.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AcademicYear::findOrFail($id)->delete();
        return redirect('academicyear')->with('success-message', __('tasks.success'));
    }
}
