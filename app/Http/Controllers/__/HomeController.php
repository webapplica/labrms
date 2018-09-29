<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::check()) {
            return view('dashboard.index');
        }

        return redirect('login');

        return view('welcome')
                ->with('isPlainBackground', true)
                ->with('bodyBackgroundColor', 'whtie');
    }
}
