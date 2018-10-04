<?php

namespace App\Http\Controllers\maintenance\software;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\Software\License\AddLicense;
use App\Commands\Software\License\RemoveLicense;

class LicenseController extends Controller
{

    /**
     * Add new license
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function store(Request $request, int $id)
    {
        $this->dispatch(new AddLicense($request, $id));
        return back()->with('success-message', __('tasks.success'));
    }

    /**
     * Remove license from the software
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function remove(Request $request, int $id)
    {
        $this->dispatch(new RemoveLicense($request, $id));
        return back()->with('success-message', __('tasks.success'));
    }
}
