<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PasswordResetController extends Controller
{

    /**
     * Display the password reset form
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        return view('auth.request_password_reset');
    }

    /**
     * Send a link to the intended url for resetting password
     *
     * @param Request $request
     * @return void
     */
    public function send(Request $request)
    {
        return redirect('/')->with('success-message', __('tasks.success'));
    }

    /**
     * Display the form for replacing password
     *
     * @param Request $request
     * @return void
     */
    public function form(Request $request)
    {
        return view('auth.password_reset_form');
    }

    /**
     * Input the new password for the user using the intended url
     *
     * @param Request $request
     * @return void
     */
    public function reset(Request $request)
    {
        return redirect('/')->with('success-message', __('tasks.success'));
    }
}
