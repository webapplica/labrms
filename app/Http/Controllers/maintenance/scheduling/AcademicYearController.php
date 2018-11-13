<?php

namespace App\Http\Controllers\Maintenance\Scheduling;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Scheduling\AcademicYear;
use App\Commands\AcademicYear\AddAcademicYear;
use App\Commands\AcademicYear\UpdateAcademicYear;
use App\Commands\AcademicYear\RemoveAcademicYear;

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

        return view('maintenance.academicyear.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $currentDate = Carbon::now()->addYear(1);
        $endDate = Carbon::now()->addMonths(6);
        return view('maintenance.academicyear.create', compact('currentDate', 'endDate'));
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = AcademicYear::findOrFail($id);
        return view('maintenance.academicyear.edit', compact('academicyear'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AcademicYear  $academicYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
    public function destroy(Request $request, $id)
    {
        $this->dispatch(new RemoveAcademicYear($request, $id));
        return redirect('academicyear')->with('success-message', __('tasks.success'));
    }
}
