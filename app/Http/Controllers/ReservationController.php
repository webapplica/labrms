<?php

namespace App\Http\Controllers;

use App;
use Auth;
use Mail;
use Session;
use App\User;
use Validator;
use App\Purpose;
use App\ItemType;
use Carbon\Carbon;
use App\Inventory;
use App\ItemProfile;
use App\Reservation;
use App\SpecialEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class ReservationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if(Request::ajax())
		{

			$reservation = App\Reservation::orderBy('created_at','desc')
								->get();
			return datatables($reservation)->toJson();
		}

		return view('reservation.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		$date = Reservation::thirdWorkingDay(Carbon::now());
		$items = App\Item::enabledReservation()->pluck('property_number', 'id');
		$rooms = App\Room::pluck('name', 'id');
		$purposes = App\Purpose::pluck('title', 'id');

		return view('reservation.create')
				->with('date',$date)
				->with('items', $items)
				->with('rooms', $rooms)
				->with('purposes', $purposes);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{		
		
		$location = $this->sanitizeString(Input::get('location'));
		$purpose = $this->sanitizeString(Input::get('purpose'));
		$dateofuse = $this->sanitizeString(Input::get('dateofuse'));
		$time_start = $this->sanitizeString(Input::get('time_start'));
		$time_end = $this->sanitizeString(Input::get('time_end'));
		$faculty = $this->sanitizeString(Input::get('name'));
		$items = Input::get('items');
		$remark = '';

		/*
		|--------------------------------------------------------------------------
		|
		| 	temporary time
		|	used for validation
		|
		|--------------------------------------------------------------------------
		|
		*/
		$time_start_temp = Carbon::parse($time_start);
		$time_end_temp = Carbon::parse($time_end); 

		/*
		|--------------------------------------------------------------------------
		|
		| 	initialize laboratory operation time
		|
		|--------------------------------------------------------------------------
		|
		*/
		$lab_start_time = Carbon::parse('7:30 AM'); 
		$lab_end_time = Carbon::parse('9:00 PM');
		/*
		|--------------------------------------------------------------------------
		|
		| 	check if time inputted is in laboratory operation time
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

		/*
		|--------------------------------------------------------------------------
		|
		| 	Laboratory Head and assistant disregard 3 day reservation rule
		|
		|--------------------------------------------------------------------------
		|
		*/
		if( Auth::user()->accesslevel != 0 && Auth::user()->accesslevel != 1 ) // the original value
		// if( true ) // debugging purpose only
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	date of use must not be greater than 3 days
			|
			|--------------------------------------------------------------------------
			|
			*/

			$current_date = Carbon::now();
			
			if(!Carbon::parse($dateofuse)->isSameDay(Reservation::thirdWorkingDay($current_date)))
			{
				return redirect('reservation/create')
						->withInput()
						->withErrors(['Reservation must occur 3 working days before usage']);
			}	

		}

		if(Input::has('items'))
		{
			$items = $this->sanitizeString(implode(Input::get('items'),','));
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	initiate values
		|	set approval to undecided
		|	set remarks to pending
		|
		|--------------------------------------------------------------------------
		|
		*/
		$approval = 0;
		// $remark = 'pending';

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check if purpose is in the list
		|	approved reservation if found
		|
		|--------------------------------------------------------------------------
		|
		*/
		// $purpose_info = Purpose::title($purpose)
		// 						->orWhere('title','like','%' . $purpose .'%')
		// 						->first();

		/*
		|--------------------------------------------------------------------------
		|
		| 	if purpose is in purpose table
		|
		|--------------------------------------------------------------------------
		|
		*/
		// if(count($purpose_info) > 0)
		// {
		// 	$approval = 1;
		// 	$remark = 'Administrator Priviledge';
		// }

		/*
		|--------------------------------------------------------------------------
		|
		| 	Administrator
		|	if reservation exists, override the existing
		|	check purpose if existing
		|	check current purpose
		|	replace if rank is higher
		|	change remark of old to 'denied due to lower priority'
		|	change old approval to 2
		|
		|--------------------------------------------------------------------------
		|
		*/
		if( Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 )
		{
			$approval = 1;
			$remark = 'Administrator Priviledge';
		}
		
		if(Auth::user()->accesslevel == 0)
		{
			//disabled
			//process cancelled
		}

		/*
		|--------------------------------------------------------------------------
		|
		|	check if purpose is user defined
		|	or not on list
		|	if not on list
		|	use description field as purpose
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Input::has('contains'))
		{
			$purpose = $this->sanitizeString(Input::get('description'));
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	If the user is faculty, use the users information
		|
		|--------------------------------------------------------------------------
		|
		*/
		if(Auth::user()->type == 'faculty')
		{
			$faculty = Auth::user()->lastname . ', ' . Auth::user()->firstname . " " . Auth::user()->middlename;
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	instantiate time
		|
		|--------------------------------------------------------------------------
		|
		*/
		$time_start = Carbon::parse($dateofuse . " " . $time_start);
		$time_end = Carbon::parse($dateofuse . " " . $time_end);

		/*
		|--------------------------------------------------------------------------
		|
		| 	Check and replace existing reservation
		|
		|--------------------------------------------------------------------------
		|
		*/
		// $reservation = Reservation::hasReserved($time_start,$time_end);
		// if( count($reservation)  > 0 && $reservation )
		// {
		// 	existing reservation must 
		// 	$purpose_1 = Purpose::where('title','like',"%" . $reservation->purpose . "%")->first();
		// 	$purpose_2 = Purpose::where('title','like',"%" . $purpose . "%")->first();

		// 	if(count($purpose_1) > 0 && count($purpose_2) > 0)
		// 	{
		// 		if(isset($purpose_1->points) && isset($purpose_2->points))
		// 		{
		// 			if($purpose_1->points < $purpose_2->points)
		// 			{	
		// 				$reservation->remark = 'Cancelled due to having lower priority';
		// 				$reservation->approval = 2;
		// 				$reservation->save();
		// 			}
		// 		}
		// 	}
		// }
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	validator ...
		|
		|--------------------------------------------------------------------------
		|
		*/
		$validator = Validator::make([
			'Items' => $items,
			'Location' => $location,
			'Date of use' => $dateofuse,
			'Time started' => $time_start,
			'Time end' => $time_end,
			'Purpose' => $purpose,
			'Faculty-in-charge' => $faculty
		],Reservation::$rules);

		if($validator->fails())
		{
			return redirect('reservation/create')
					->withInput()
					->withErrors($validator);
		}

		/*
		|--------------------------------------------------------------------------
		|
		| 	convert items type to propertynumber
		|
		|--------------------------------------------------------------------------
		|
		*/
		$_items = [];	

		foreach(explode(",",$items) as $item)
		{

			if( $this->hasData($item) == false )
			{
				return redirect('reservation/create')
						->withInput()
						->withErrors(["You need to chose a valid item for reservation"]);
			}

			$_temp = ReservationItemsView::unreserved(
						$dateofuse,
						$time_start->format('h:i A'),
						$time_end->format('h:i A')
			)->filter($item)->pluck('id')->first();

			/*
			|--------------------------------------------------------------------------
			|
			| 	no more items to borrow
			|
			|--------------------------------------------------------------------------
			|
			*/
			if( count($_temp) == 0 || $_temp == null ||$_temp == '')
			{
				return redirect('reservation/create')
						->withInput()
						->withErrors(["No more $item available for reservation"]);
			}	

			array_push($_items,$_temp);
		}
		
		/*
		|--------------------------------------------------------------------------
		|
		| 	reservation create
		|
		|--------------------------------------------------------------------------
		|
		*/
		$reservation = new Reservation;
		$reservation->user_id = Auth::user()->id;
		$reservation->timein = $time_start;
		$reservation->timeout = $time_end;
		$reservation->purpose = $purpose;
		$reservation->location = $location;
		$reservation->approval = $approval;
		$reservation->remark = $remark;
		$reservation->facultyincharge = $faculty;
		$reservation->save();

		$reservation->itemprofile()->attach($_items);

		Session::flash('success-message','Reservation Created');
		return redirect('reservation/create');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		if(Request::ajax())
		{
			return json_encode(Reservation::find($id));
		}

		$reservation = Reservation::find($id);

		if(count($reservation) <= 0)
		{
			return redirect('dashboard');
		}

		return view('reservation.show')
				->with('reservation',$reservation);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		return view('pagenotfound');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return view('pagenotfound');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		return view('pagenotfound');
	}

	/**
	*
	*	check if item is reserved
	*	@param start -> starting time of reservation
	*	@param end -> end time of reservation
	*	@return $reservation info
	*
	*/
	public function hasReserved($start,$end)
	{
		if( Request::ajax() )
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if reserved
			|
			|--------------------------------------------------------------------------
			|
			*/
			$reservation = Reservation::hasReserved($time_start,$time_end);
			if( count($reservation)  > 0 && $reservation )
			{
				return json_encode($reservation);
			}

			return json_encode('error');
		}
	}

	/**
	*
	*	@param id
	*	@return 'success','error'
	*
	*/
	public function approve($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);

			/*
			|--------------------------------------------------------------------------
			|
			| 	calls approve method in reservation
			|
			|--------------------------------------------------------------------------
			|
			*/
			$reservation = Reservation::approve($id);

			/*
			|--------------------------------------------------------------------------
			|
			| 	if success
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(count($reservation) > 0)
			{
		        $user = User::findOrFail($reservation->user_id);
		        $subject = 'Reservation Disapproval Notice';

		        try{

			        Mail::send(['html'=>'reservation.notice'], ['reservation' =>$reservation], function ($message) use ($subject,$user) {
			            $message->from('pup.ccis.server@gmail.com', 'PUP-CCIS Server Community');
			            $message->subject($subject);
			            $message->to($user->email)->cc($user->email);
			        });

		        } catch ( Exception $e ) {}

				return json_encode('success');
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	if error occurred
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode('error');
		}
	}

	/**
	*
	*	@param id
	*	@param reason
	*	@return 'success','error'
	*
	*/
	public function disapprove($id)
	{
		if(Request::ajax())
		{
			$id = $this->sanitizeString($id);
			$reason = $this->sanitizeString(Input::get('reason'));

			/*
			|--------------------------------------------------------------------------
			|
			| 	calls disapprove method in reservation
			|
			|--------------------------------------------------------------------------
			|
			*/
			$reservation = Reservation::disapprove($id,$reason);

			/*
			|--------------------------------------------------------------------------
			|
			| 	if success
			|
			|--------------------------------------------------------------------------
			|
			*/
			if(count($reservation) > 0)
			{
		        $user = User::findOrFail($reservation->user_id);
		        $subject = 'Reservation Disapproval Notice';

		        try
		        {

			        Mail::send(['html'=>'reservation.notice'],  ['reservation' =>$reservation] , function ($message) use ($subject,$user) {
			            $message->from('pup.ccis.server@gmail.com', 'PUP-CCIS Server Community');
			            $message->subject($subject);
			            $message->to($user->email)->cc($user->email);
			        });

		        } catch (Exception $e) {}

				return json_encode('success');
			}

			/*
			|--------------------------------------------------------------------------
			|
			| 	if error occurred
			|
			|--------------------------------------------------------------------------
			|
			*/
			return json_encode('error');
		}
	}

	public function claim()
	{

		/*
		|--------------------------------------------------------------------------
		|
		| 	get the reservation id
		|
		|--------------------------------------------------------------------------
		|
		*/
		$id =  Input::get('id');

		/*
		|--------------------------------------------------------------------------
		|
		| 	clean the reservation id
		|
		|--------------------------------------------------------------------------
		|
		*/
		$id = $this->sanitizeString(Input::get('id'));

		/*
		|--------------------------------------------------------------------------
		|
		| 	set the reservation status to claimed
		|
		|--------------------------------------------------------------------------
		|
		*/

		// $reservation = Reservation::setStatusAsClaimed($id);

		/*
		|--------------------------------------------------------------------------
		|
		| 	redirect to lend log
		|
		|--------------------------------------------------------------------------
		|
		*/
		return redirect("lend/create?reservation=$id");
	}
}
