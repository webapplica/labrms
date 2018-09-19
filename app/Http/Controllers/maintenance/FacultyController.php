<?php

namespace App\Http\Controllers\Maintenance;

use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
            return datatables(Faculty::all())->toJson();
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
    public function store(Request $request)
    {

        $this->validate($request, [
            'firstname' => 'required|between:2,100|string',
            'middlename' => 'min:2|max:50|string',
            'lastname' => 'required|min:2|max:50|string',
            'contact_number' => 'size:11|string',
            'email' => 'email',
            'suffix' => 'max:3',
        ]);

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
        $faculty = App\Faculty::find($id);

        if( $faculty->count() <= 0 )
        {
        Session::flash('success-message','Invalid Faculty Information');
            return redirect('faculty');
        }

        return view('faculty.edit')
                ->with('title','Faculty')
                ->with('faculty',$faculty);
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

        $this->validate($request + ['id' => $id], [
            'id' => 'required|integer|exists:faculties,id',
            'firstname' => 'required|between:2,100|string',
            'middlename' => 'min:2|max:50|string',
            'lastname' => 'required|min:2|max:50|string',
            'contact_number' => 'size:11|string',
            'email' => 'email',
            'suffix' => 'max:3',
        ]);

        $faculty = Faculty::find($id);
        $faculty->title = $title;
        $faculty->firstname = $firstname;
        $faculty->middlename = $middlename;
        $faculty->lastname = $lastname;
        $faculty->contactnumber = $contactnumber;
        $faculty->email = $email;
        $faculty->suffix = $suffix;
        $faculty->save();

        return redirect('faculty')->with('success-message', __('tasks.success'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->validate(['id' => $id], [
            'id' => 'required|integer|exists:faculties,id',
        ]);

        Faculty::find($id)->delete();
        if($request->ajax()) {
            return response()->json([
                'message' => __('tasks.success')
            ], 200);
        }

        return redirect('faculty')->with('success-message', __('tasks.success'));
    }
}
