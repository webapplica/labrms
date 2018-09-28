
		if($request->ajax()) {
			$id = $this->sanitizeString($id);
			$reason = $this->sanitizeString($request->get('reason'));
			$reservation = App\Reservation::disapprove($id, $reason);
			$subject = 'Reservation Disapproval Notice';
	
			if( $reservation->count() > 0) {
				$response = $this->sendMail($reservation, $subject);
			}

			$success_message = isset($response['success_message']) ?: "";
			$error_message = isset($response['error_message']) ?: "";
			$response_code = isset($response['response_code']) ?: 200;

			return response()->json([
				'messages' => $success_message,
				'errors' => $error_message,
			], $response_code);
		}