
		$location = $this->sanitizeString($request->get('location'));
		$purpose = $this->sanitizeString($request->get('purpose'));
		$dateofuse = $this->sanitizeString($request->get('dateofuse'));
		$time_start = $this->sanitizeString($request->get('time_start'));
		$time_end = $this->sanitizeString($request->get('time_end'));
		$faculty = $this->sanitizeString($request->get('name'));
		$items = $request->get('items');
		$remark = '';
		$description = $this->sanitizeString($request->get('description'));

		if( App\Purpose::where('id', '=', $purpose)->count() > 0 ) {
			$purpose = App\Purpose::where('id', '=', $purpose)->pluck('title')->first();
		} else {
			$purpose = $description;
		}

		$time_start_temp = Carbon::parse($time_start);
		$time_end_temp = Carbon::parse($time_end); 

		$lab_start_time = Carbon::parse('7:30 AM'); 
		$lab_end_time = Carbon::parse('9:00 PM');
		
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

		if( Auth::user()->accesslevel != 0 && Auth::user()->accesslevel != 1 )
		{
			$current_date = Carbon::now();
			
			if(! Carbon::parse($dateofuse)->isSameDay( Reservation::thirdWorkingDay($current_date))) {
				return redirect('reservation/create')
						->withInput()
						->withErrors(['Reservation must occur 3 working days before usage']);
			}	

		}

		if($request->has('items')) {
			$items = $this->sanitizeString( implode( $request->get('items'), ',' ) );
		}

		$approval = 0;
		if( Auth::user()->accesslevel == 0 || Auth::user()->accesslevel == 1 ) {
			$approval = 1;
			$remark = 'Administrator Priviledge';
		}
		
		if(Auth::user()->accesslevel == 0) {
			//disabled
			//process cancelled
		}

		if($request->has('contains')) {
			$purpose = $this->sanitizeString($request->get('description'));
		}

		$time_start = Carbon::parse($dateofuse . " " . $time_start);
		$time_end = Carbon::parse($dateofuse . " " . $time_end);

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
