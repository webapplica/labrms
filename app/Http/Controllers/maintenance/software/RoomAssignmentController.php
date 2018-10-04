<?php

namespace App\Http\Controllers\maintenance\software;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Commands\Software\Room\AssignRoom;
use App\Commands\Software\Room\UnassignRoom;

class RoomAssignmentController extends Controller
{

    /**
     * Assign to a room
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function store(Request $request, int $id)
    {
        $this->dispatch(new AssignRoom($request, $id));
        return back()->with('success-message', __('tasks.success'));
    }

    /**
     * Remove from room from the software
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function remove(Request $request, int $id, int $room_id)
    {
        $this->dispatch(new UnassignRoom($request, $id, $room_id));
        return back()->with('success-message', __('tasks.success'));
    }
}
