<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket\Ticket;
use Illuminate\Support\Facades\Auth;

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
            $tickets = Ticket::paginate(20);
            return view('dashboard.index', compact('tickets'));
        }

        return redirect('login');
    }
}
