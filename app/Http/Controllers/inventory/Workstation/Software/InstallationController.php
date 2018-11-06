<?php

namespace App\Http\Controllers\inventory\workstation\software;

use Illuminate\Http\Request;
use App\Models\Software\Software;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Commands\Workstation\Software\InstallSoftware;

class InstallationController extends Controller
{

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($id)
	{
		$workstation = Workstation::findOrFail($id);
		$existingSoftwareIds = $workstation->softwares->pluck('id');
		$softwares = Software::whereNotIn('id', $existingSoftwareIds)->pluck('name', 'id');
		
		return view('workstation.software.create', compact('workstation', 'softwares'));
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request, $id)
	{
		$this->dispatch(new InstallSoftware($request, $id));
        return redirect("workstation/$id/software")
                    ->with('success-message', __('tasks.success'));
	}
}
