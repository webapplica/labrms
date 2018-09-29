<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\FacultyRequest\FacultyStoreRequest;
use App\Http\Requests\FacultyRequest\FacultyUpdateRequest;

class FacultyController extends Controller
{

    public function __construct()
    {
        View::share('title', 'Faculty');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            return datatables( Faculty::all() )->toJson();
        }

        return view('faculty.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('faculty.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacultyStoreRequest $request)
    {
        Faculty::create($request);
        return redirect('faculty')->with('success-message', __('tasks.success'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('faculty.edit')
                ->with('faculty', Faculty::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FacultyUpdateRequest $request, $id)
    {
        $faculty = Faculty::findOrFail($id)->update($request);
        return redirect('faculty')->with('success-message', __('tasks.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Faculty::findOrFail($id)->delete();
        return redirect('faculty')->with('success-message', __('tasks.success'));
    }
}
