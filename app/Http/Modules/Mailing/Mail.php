
	/**
	 * Undocumented function
	 *
	 * @param [type] $reservation
	 * @param [type] $user
	 * @return void
	 */
	public function sendMail( $reservation, $subject)
	{
		$response_code = 200;
		$success_message = "";
		$error_message = "";

		$user = App\User::findOrFail($reservation->user_id);

		try
		{
			Mail::send(['html'=>'reservation.notice'],  ['reservation' =>$reservation] , function ($message) use ($subject, $user) {
				$message->from('pup.ccis.server@gmail.com', 'PUP-CCIS Server Community');
				$message->subject($subject);
				$message->to($user->email)->cc($user->email);
			});

			$success_message = 'Reservation successfully updated';
		} catch ( \Exception $e) {
			$response_code = 500;
			$error_message = 'Emailing services failed';

		}

		return [
			'response_code' => $response_code,
			'error_message' => $error_message,
			'success_message' => $success_message,
		];
	}