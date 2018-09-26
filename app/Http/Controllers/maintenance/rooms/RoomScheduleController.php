<?php

namespace App\Http\Controllers\Maintenance\Room;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomScheduleController extends Controller
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
            if(Input::has('room'))
            {
                $room = $this->sanitizeString(Input::get('room'));

                return json_encode([ 'data' => App\RoomScheduleView::current()->where('room_id','=',$room)->get() ]);

            }

            return json_encode([ 'data' => App\RoomScheduleView::current()->where('room_id','=','1')->get() ]);   
        }
        
        if(Input::has('room'))
        {
            $room = $this->sanitizeString(Input::get('room'));

            $roomschedule = App\RoomScheduleView::current()->where('room_id','=',$room)->get();


            return view('schedule.room.index')
                    ->with('roomschedule',$roomschedule)
                    ->with('rooms',App\Room::all());
        }

        $roomschedule = App\RoomScheduleView::current()->where('room_id','=','1')->get();

        return view('schedule.room.index')
                ->with('roomschedule',$roomschedule)
                ->with('rooms',App\Room::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $purpose = App\Purpose::pluck('title','title');
        $room = App\Room::pluck('name','id');
        $faculty = App\Faculty::select(DB::raw("CONCAT(lastname,' ',firstname,' ',middlename) as name"),'id')->pluck('name','id');
        return view('schedule.room.create')
                    ->with('purpose',$purpose)
                    ->with('room',$room)
                    ->with('faculty',$faculty);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dateofuse = $this->sanitizeString(Input::get('dateofuse'));
        $purpose = $this->sanitizeString(Input::get('purpose'));
        $room = $this->sanitizeString(Input::get("room"));
        $timein = $this->sanitizeString(Input::get("timestart"));
        $timeout = $this->sanitizeString(Input::get('timeend'));
        $section = $this->sanitizeString(Input::get("section"));
        $faculty = $this->sanitizeString(Input::get('faculty'));

        $approval = 1;
        $remark = '';

        $room_name = App\Room::where('id','=',$room)->pluck('name')->first();

        $faculty_name = App\Faculty::where('id','=',$faculty)
                                            ->select(DB::raw("CONCAT(lastname,' ',firstname,' ',middlename) as name"))
                                            ->pluck('name')
                                            ->first();

        if(Input::has('contains'))
        {
            $purpose = $this->sanitizeString(Input::get("description"));   
        }

        /*
        |--------------------------------------------------------------------------
        |
        |   temporary time
        |   used for validation
        |
        |--------------------------------------------------------------------------
        |
        */
        $time_start_temp = Carbon::parse($timein);
        $time_end_temp = Carbon::parse($timeout); 

        /*
        |--------------------------------------------------------------------------
        |
        |   initialize laboratory operation time
        |
        |--------------------------------------------------------------------------
        |
        */
        $lab_start_time = Carbon::parse('7:30 AM'); 
        $lab_end_time = Carbon::parse('9:00 PM');
        /*
        |--------------------------------------------------------------------------
        |
        |   check if time inputted is in laboratory operation time
        |
        |--------------------------------------------------------------------------
        |
        */
        if($time_start_temp->between( $lab_start_time,$lab_end_time ) && $time_end_temp->between( $lab_start_time,$lab_end_time ))
        {
            if($time_start_temp >= $time_end_temp)
            {

                return redirect('reservation/create')
                        ->withInput()
                        ->withErrors(['Time start must be less than time end']);
            }
        }
        else
        {
            return redirect('reservation/create')
                    ->withInput()
                    ->withErrors(['Reservation must occur only from 7:30 AM - 9:00 PM']);
        }

        $time_start = Carbon::parse($dateofuse . " " . $timein);
        $time_end = Carbon::parse($dateofuse . " " . $timeout);

        DB::beginTransaction();
        
        /*
        |--------------------------------------------------------------------------
        |
        |   validator ...
        |
        |--------------------------------------------------------------------------
        |
        */
        $validator = Validator::make([
            'Room' => $room,
            'Room Name' => $room_name,
            'Date of use' => $dateofuse,
            'Time started' => $time_start,
            'Time end' => $time_end,
            'Purpose' => $purpose,
            'Faculty-in-charge' => $faculty_name
        ],App\Reservation::$roomReservationRules);

        if($validator->fails())
        {
            return redirect('room/scheduling/create')
                    ->withInput()
                    ->withErrors($validator);
        }

        /*
        |--------------------------------------------------------------------------
        |
        |   reservation create
        |
        |--------------------------------------------------------------------------
        |
        */
        $reservation = new Reservation;
        $reservation->user_id = $faculty;
        $reservation->timein = $time_start;
        $reservation->timeout = $time_end;
        $reservation->purpose = $purpose;
        $reservation->location = $room_name;
        $reservation->approval = $approval;
        $reservation->remark = $remark;
        $reservation->facultyincharge = $faculty_name;
        $reservation->save();

        $reservation->room()->attach($room);

        DB::commit();

        Session::flash('success-message','Reservation Created');
        return redirect('room/scheduling');
    }
}
