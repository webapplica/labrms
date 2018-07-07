<?php

namespace App\Http\Controllers;

use App;
use Session;
use Validator;
use Illuminate\Http\Request;

class FacultiesController extends Controller
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
            $units = App\Faculty::all();
            return json_encode([
                'data' => $units
            ]);
        }

        return view('faculty.index')
                ->with('title','Faculty');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('faculty.create')
                ->with('title','Faculty');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $title = $this->sanitizeString($request->get('title'));
        $firstname = $this->sanitizeString($request->get("firstname"));
        $middlename = $this->sanitizeString($request->get("middlename"));
        $lastname = $this->sanitizeString($request->get("lastname"));
        $contactnumber = $this->sanitizeString($request->get("contactnumber"));
        $email = $this->sanitizeString($request->get("email"));
        $suffix = $this->sanitizeString($request->get("suffix"));

        $validator = Validator::make([
            'First name' => $firstname,
            'Middle name' => $middlename,
            'Last name' => $lastname,
            'Contact number' => $contactnumber,
            'Email' => $email
        ],App\Faculty::$rules);

        if($validator->fails())
        {
            return back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $faculty = new App\Faculty;
        $faculty->title = $title;
        $faculty->firstname = $firstname;
        $faculty->middlename = $middlename;
        $faculty->lastname = $lastname;
        $faculty->contactnumber = $contactnumber;
        $faculty->email = $email;
        $faculty->suffix = $suffix;
        $faculty->save();

        Session::flash('success-message','Faculty Information Created');
        return redirect('faculty');
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

        $title = $this->sanitizeString($request->get('title'));
        $firstname = $this->sanitizeString($request->get("firstname"));
        $middlename = $this->sanitizeString($request->get("middlename"));
        $lastname = $this->sanitizeString($request->get("lastname"));
        $contactnumber = $this->sanitizeString($request->get("contactnumber"));
        $email = $this->sanitizeString($request->get("email"));
        $suffix = $this->sanitizeString($request->get("suffix"));

        $validator = Validator::make([
            'First name' => $firstname,
            'Middle name' => $middlename,
            'Last name' => $lastname,
            'Contact number' => $contactnumber,
            'Email' => $email
        ],App\Faculty::$updateRules);

        if($validator->fails())
        {
            return back()
                    ->withInput()
                    ->withErrors($validator);
        }

        $faculty = App\Faculty::find($id);
        $faculty->title = $title;
        $faculty->firstname = $firstname;
        $faculty->middlename = $middlename;
        $faculty->lastname = $lastname;
        $faculty->contactnumber = $contactnumber;
        $faculty->email = $email;
        $faculty->suffix = $suffix;
        $faculty->save();

        Session::flash('success-message','Faculty Information Updated');
        return redirect('faculty');
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
            $faculty = App\Faculty::find($id);

            if(count($faculty) <= 0)
            {
                return json_encode('error');
            }

            $faculty->delete();
            return json_encode('success');
        }

        $faculty = App\Faculty::find($id);
        $faculty->delete();

       Session::flash('success-message','Faculty Removed');
        return redirect('faculty');
    }
}
