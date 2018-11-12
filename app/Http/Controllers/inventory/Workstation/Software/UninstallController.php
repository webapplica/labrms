<?php

namespace App\Http\Controllers\Inventory\Workstation\Software;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Workstation\Workstation;
use App\Commands\Workstation\Software\UninstallSoftware;

class UninstallController extends Controller
{

    /**
     * Display the form for removing the software from the workstation
     *
     * @param int $workstation
     * @param int $software
     * @return Response
     */
    public function showForm(Request $request, $workstation, $software)
    {
        $workstation = Workstation::with('softwares')->findOrFail($workstation);
        $software = $workstation->softwares()->findOrFail($software);

        return view('workstation.software.uninstall', compact('workstation', 'software'));
    }

    /**
     * Removes the link between the workstation and the software
     *
     * @param int $workstation
     * @param int $software
     * @return Response
     */
    public function uninstall(Request $request, $workstation, $software)
    {
        $this->dispatch(new UninstallSoftware($request, $workstation, $software));
        return redirect("workstation/{$workstation}/software")
                ->with('success-message', __('tasks.success'));
    }
}
