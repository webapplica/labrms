<?php

namespace App\Http\Controllers;

use App;
use Auth;
use Mail;
use Session;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		if($request->ajax())
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
	public function create(Request $request)
	{
		$date = App\Reservation::thirdWorkingDay(Carbon::now());
		$items = App\Item::enabledReservation()->pluck('property_number', 'id');
		$rooms = App\Room::pluck('name', 'id');
		$purposes = App\Purpose::pluck('title', 'id');
		$faculties =  App\Faculty::all();

		return view('reservation.create')
				->with('date',$date)
				->with('items', $items)
				->with('rooms', $rooms)
				->with('purposes', $purposes)
				->with('faculties', $faculties);
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{		
		$location = $this->sanitizeString($request->get('location'));
		$purpose = $this->sanitizeString($request->get('purpose'));
		$dateofuse = $this->sanitizeString($request->get('dateofuse'));
		$time_start = $this->sanitizeString($request->get('time_start'));
		$time_end = $this->sanitizeString($request->get('time_end'));
		$faculty = $this->sanitizeString($request->get('name'));
		$items = $request->get('items');
		$remark = '';
		$description = $this->sanitizeString($request->get('description'));

		// set the default value for purpose
		if( App\Purpose::where('id', '=', $purpose)->count() > 0 ) {
			$purpose = App\Purpose::where('id', '=', $purpose)->pluck('title')->first();
		} else {
			$purpose = $description;
		}

		// temporary time
		// used for validation
		$time_start_temp = Carbon::parse($time_start);
		$time_end_temp = Carbon::parse($time_end); 

		// initialize laboratory operations time
		$lab_start_time = Carbon::parse('7:30 AM'); 
		$lab_end_time = Carbon::parse('9:00 PM');
		
		// checked if the time inputted is in the laboratory operations time
		if($time_start_temp->between( $lab_start_time,$lab_end_time ) && $time_end_temp->between( $lab_start_time,$lab_end_time )) {
			if($time_start_temp >= $time_end_temp) {

				return redirect('reservation/create')
						->withInput()
						->withErrors(['Time start must be less than time end']);
			}
		} else {
			return redirect('reservation/create')
					->withInput()
					->withErrors(['Reservation must occur only from 7:30 AM - 9:00 PM']);
		}

		// laboratory operations time does not apply to laboratory head
		// and laboratory assistant
		if( Auth::user()->accesslevel != 0 && Auth::user()->accesslevel != 1 ) // the original value
		// if( true ) // debugging purpose only
		{

			// date of use must not be greater than 3 working days
			$current_date = Carbon::now();
			
			if(! Carbon::parse($dateofuse)->isSameDay( Reservation::thirdWorkingDay($current_date))) {
				return redirect('reservation/create')
						->withInput()
						->withErrors(['Reservation must occur 3 working days before usage']);
			}	

		}

		// check if the request has an array of items stored
		if($request->has('items')) {
			$items = $this->sanitizeString( implode( $request->get('items'), ',' ) );
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

		// Administrator
		// if reservation exists, override the existing
		// check purpose if existing
		// check current purpose
		// replace if rank is higher
		// change remark of old to 'denied due to lower priority'
		// change old approval to 2
		if( Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 ) {
			$approval = 1;
			$remark = 'Administrator Priviledge';
		}
		
		if(Auth::user()->accesslevel == 0) {
			//disabled
			//process cancelled
		}

		// check if purpose is user defined
		// or not on list
		// if not on list
		// use description field as purpose
		if($request->has('contains')) {
			$purpose = $this->sanitizeString($request->get('description'));
		}

		// instantiate time
		$time_start = Carbon::parse($dateofuse . " " . $time_start);
		$time_end = Carbon::parse($dateofuse . " " . $time_end);

		// Check and replace existing reservation
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
		
		// validator ...
		$validator = Validator::make([
			'Items' => $items,
			'Location' => $location,
			'Date of use' => $dateofuse,
			'Time started' => $time_start,
			'Time end' => $time_end,
			'Purpose' => $purpose,
			'Faculty-in-charge' => $faculty
		], App\Reservation::$rules);

		if($validator->fails()) {
			return redirect('reservation/create')
					->withInput()
					->withErrors($validator);
		}

		// checks if each item is valid for reservation
		$temp_items = explode(",", $items);
		$items = array();

		foreach( $temp_items as $item ) {

			$items[] = App\Item::where('id', '=', $item)->pluck('id')->first();

			// no more items to borrow
			if( count($items) == 0 || $items == null ) {
				return redirect('reservation/create')
						->withInput()
						->withErrors(["No more $item available for reservation"]);
			}	
		}
		
		$faculty = App\Faculty::find($faculty);
		$faculty_id = App\User::where('lastname', '=', $faculty->lastname)->where('firstname', '=', $faculty->firstname)->where('middlename', '=', $faculty->middlename)->pluck('id')->first();

		$reservation = new App\Reservation;
		$reservation->user_id = Auth::user()->id;
		$reservation->start = $time_start;
		$reservation->end = $time_end;
		$reservation->purpose = $purpose;
		$reservation->location = $location;
		$reservation->is_approved = $approval;
		$reservation->remarks = $remark;
		$reservation->accountable = $faculty->full_name;
		$reservation->faculty_id = $faculty_id;
		$reservation->save();

		$reservation->item()->attach($items);

		Session::flash('success-message','Reservation Created');
		return redirect('reservation/create');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(Request $request, $id)
	{
		if($request->ajax())
		{
			return json_encode(App\Reservation::find($id));
		}

		$reservation = App\Reservation::find($id);

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
	public function edit(Request $request, $id)
	{
		return view('pagenotfound');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Request $request, $id)
	{
		return view('pagenotfound');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Request $request, $id)
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
	public function hasReserved(Request $request, $start,$end)
	{
		if( $request->ajax() )
		{

			/*
			|--------------------------------------------------------------------------
			|
			| 	Check if reserved
			|
			|--------------------------------------------------------------------------
			|
			*/
			$reservation = App\Reservation::hasReserved($time_start,$time_end);
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
	public function approve(Request $request, $id)
	{
		if($request->ajax())
		{
			$id = $this->sanitizeString($id);
			$reservation = App\Reservation::approve($id);
			$subject = 'Reservation Approval Notice';
			$response_code = 200;
			$success_message = [];
			$error_message = [];
	
			if( $reservation->count() > 0)
			{
				$user = App\User::findOrFail($reservation->user_id);
	
				try
				{
					Mail::send(['html'=>'reservation.notice'],  ['reservation' =>$reservation] , function ($message) use ($subject,$user) {
						$message->from('pup.ccis.server@gmail.com', 'PUP-CCIS Server Community');
						$message->subject($subject);
						$message->to($user->email)->cc($user->email);
					});
	
					$success_messages = 'Reservation successfully updated';
				} catch ( \Exception $e) {
					$response_code = 500;
					$error_message = 'Emailing services failed';
	
				}
			}

			return response()->json([
				'messages' => $success_messages,
				'errors' => $error_messages,
			], $response_code);
		}
	}

	/**
	*
	*	@param id
	*	@param reason
	*	@return 'success','error'
	*
	*/
	public function disapprove(Request $request, $id)
	{
		if($request->ajax())
		{
			$id = $this->sanitizeString($id);
			$reason = $this->sanitizeString($request->get('reason'));
			$reservation = App\Reservation::disapprove($id, $reason);
			$subject = 'Reservation Disapproval Notice';
			$response_code = 200;
			$success_message = [];
			$error_message = [];
	
			if( $reservation->count() > 0)
			{
				$user = App\User::findOrFail($reservation->user_id);
	
				try
				{
					Mail::send(['html'=>'reservation.notice'],  ['reservation' =>$reservation] , function ($message) use ($subject,$user) {
						$message->from('pup.ccis.server@gmail.com', 'PUP-CCIS Server Community');
						$message->subject($subject);
						$message->to($user->email)->cc($user->email);
					});
	
					$success_message = 'Reservation successfully updated';
				} catch ( \Exception $e) {
					$response_code = 500;
					$error_message = 'Emailing services failed';
	
				}
			}

			return response()->json([
				'messages' => $success_message,
				'errors' => $error_message,
			], $response_code);
		}
	}

	public function claim(Request $request)
	{

		$id =  $request->get('id');
		$id = $this->sanitizeString($request->get('id'));
		// $reservation = Reservation::setStatusAsClaimed($id);
		return redirect("lend/create?reservation=$id");
	}
}
