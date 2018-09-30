<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use App\Commands\Faculty\UpdateFaculty;
use App\Commands\Faculty\RegisterFaculty;
use App\Http\Requests\FacultyRequest\FacultyStoreRequest;
use App\Http\Requests\FacultyRequest\FacultyUpdateRequest; 

class FacultyController extends Controller
{
    
    /**
     * Constructor
     */
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
            return datatables(Faculty::all())->toJson();
        }

        return view('maintenance.faculty.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('maintenance.faculty.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FacultyStoreRequest $request)
    {
        $this->dispatch(new RegisterFaculty($request));
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
        $faculty = Faculty::findOrFail($id);
        return view('maintenance.faculty.edit', compact('faculty'));
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
        $this->dispatch(new UpdateFaculty($request, $id));
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
